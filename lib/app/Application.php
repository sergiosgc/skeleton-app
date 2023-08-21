<?php
namespace app;
use phpseclib3\Crypt\Blowfish;

class Application {
    use \sergiosgc\config\Config;
    public $config = null;
    public $paths = [];
    private $router = null;
    private $componentRouter = null;
    private static $_singleton = null;
    public function __construct() {
        $this->paths['root'] = realpath(__DIR__ . '/../..');
        $this->paths['config'] = realpath($this->paths['root'] . '/config');
        $this->paths['media'] = realpath($this->paths['root'] . '/media');
        $this->paths['rest'] = realpath($this->paths['root'] . '/rest');
        $this->paths['components'] = realpath($this->paths['root'] . '/components');
        foreach ($this->paths as $key => $value) if ($value === false) throw new Exception(sprintf('Path %s does not exist', $key));
        self::$_singleton = $this;

        $this->getComponentRouter()->subrouter->addPath($this->paths['components']);
        $this->getComponentRouter()->subrouter->addPath($this->paths['root'] . '/vendor/sergiosgc/html-components/components');
        $this->getComponentRouter()->subrouter->addPath($this->paths['root'] . '/vendor/sergiosgc/jsonschema-form/components');

        \sergiosgc\Localization::$singleton = new \sergiosgc\Localization([ new \sergiosgc\LocaleSourceHttpAccepts([ 'EN', 'PT' ]), new \sergiosgc\LocaleSourceFallback() ]);
        \sergiosgc\translation\Translation::singleton()->setCache(new \sergiosgc\translation\RedisCache(\app\app()->getConfigWithDefault('redis.host', '127.0.0.1'), \app\app()->getConfigWithDefault('redis.port', 6379)));
        \sergiosgc\translation\Translation::singleton()->setLocale(\sergiosgc\Localization::singleton()->getLocale());
        \sergiosgc\Localization::singleton()->setLocale(
            \sergiosgc\Localization::singleton()->getLocale(),
            [ LC_COLLATE, LC_CTYPE, LC_MONETARY, LC_TIME, LC_MESSAGES ]
        );

        \sergiosgc\form\Form::$componentCallback = [ '\app\Template', 'component' ];

        openlog("applog", LOG_PID | LOG_PERROR, LOG_LOCAL0);

        \sergiosgc\crud\RelationalFormOptionFetcher::register();
    }
    public static function singleton(): Application {
        if (!static::$_singleton) static::$_singleton = new Application();
        return static::$_singleton;
    }
    public function getRouter() {
        if (is_null($this->router)) {
            $this->router = new \sergiosgc\router\Templated(
                new \sergiosgc\router\Rest($this->paths['rest']), 
                $this->paths['rest'], 
                [ 'app\Template', 'compile' ], 
                [ 'application/json', 'application/json+x-froala', 'application/json+jsonschema-form', 'text/html; charset=UTF-8', 'text/csv; charset=UTF-8' ],
                'all');
        }
        return $this->router;
    }
    public function getComponentRouter() {
        if (is_null($this->componentRouter)) {
            $this->componentRouter = new \sergiosgc\router\Templated(
                new \sergiosgc\router\Filesystem([]),
                $this->paths['root'],
                [ 'app\Template', 'compile' ],
                null, 
                'all'
            );
        }
        return $this->componentRouter;
    }
    public function getDatabaseConnection($reconnect = false) {
        static $dbConnection = null;
        static $lastPid = null;
        if (!is_null($dbConnection) && $lastPid != getmypid()) $dbConnection = null; // A fork ocurred, we need cannot use parent's connection
        if ($reconnect || is_null($dbConnection)) {
            $dsn = \sergiosgc\sprintf('pgsql:host=%<host>;port=%<port>;dbname=%<database>;user=%<user>;password=%<password>',
                [ 
                    'host' => $this->getConfigWithDefault('database.host', '127.0.0.1'), 
                    'port' => $this->getConfigWithDefault('database.port', '5432'), 
                    'database' => $this->getConfig('database.database'), 
                    'user' => $this->getConfigWithDefault('database.user', 'postgres'), 
                    'password' => $this->getConfigWithDefault('database.password', 'none'), 
                ]
            );
            $dbConnection = new \PDO($dsn);
            $dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $lastPid = getmypid();
        }
        return $dbConnection;
    }
    public function url($url) {
        $args = func_get_args();
        if (count($args) > 1) $url = call_user_func_array(function_exists('\sergiosgc\sprintf') ? '\sergiosgc\sprintf' : 'sprintf', $args);
        return preg_replace('_^/_', $this->getConfigWithDefault('root_uri', '/'), $url);
    }
    public function redirect($url) {
        $args = func_get_args();
        if (count($args) > 1) $url = call_user_func_array(function_exists('\sergiosgc\sprintf') ? '\sergiosgc\sprintf' : 'sprintf', $args);
        header('Location: ' . $url);
        exit;
    }
    public function signValue($value) {
        $payload = base64_encode($value);
        $signature = hash_hmac('sha256', $payload, $this->getConfig('application.hmac_secret'));
        return sprintf('%s:%s', $payload, $signature);
    }
    public function signedValue($signedValue) {
        list($payload, $signature) = explode(':', $signedValue, 2);
        if ($signature !== hash_hmac('sha256', $payload, $this->getConfig('application.hmac_secret'))) throw new Exception('Invalid signature on value');
        $payload = base64_decode($payload);
        if (!$payload) throw new Exception('Invalid payload on signed value');
        return $payload;
    }
    public function encryptValue($value) {
        static $cipher = null;
        if (!$cipher) {
            $cipher = new Blowfish('cbc');
            $cipher->setIV(substr($this->getConfig('application.encryption_key'), 0, 8));
            $cipher->setKey(substr($this->getConfig('application.encryption_key'), 0, 8));
        }
        return bin2hex($cipher->encrypt($value));
    }
    public function decryptValue($value) {
        static $cipher = null;
        try {
            if (!$cipher) {
                $cipher = new Blowfish('cbc');
                $cipher->setIV(substr($this->getConfig('application.encryption_key'), 0, 8));
                $cipher->setKey(substr($this->getConfig('application.encryption_key'), 0, 8));
            }
            return $cipher->decrypt(hex2bin($value));
        } catch (\RunTimeException $e) {
            // Support old method. try/catch may be removed after 2023-08-01
            return openssl_decrypt( hex2bin($value), "BF-CBC", $this->getConfig('application.encryption_key'), OPENSSL_RAW_DATA, substr($this->getConfig('application.encryption_key'), 0, 8));
        }
    }
    public function notifyUser($type, $message) {
        static $sequentialId = 0;
        $value = $this->signValue(json_encode([$type, $message]));
        setcookie(sprintf("notification-%d", $sequentialId++), $value, time()+300, "/", $_SERVER['HTTP_HOST'], true, false);
    }
    public function getUserNotifications() {
        $result = [];
        foreach($_COOKIE as $name => $signed) if (substr($name, 0, strlen("notification-")) == "notification-") {
            $result[] = json_decode($this->signedValue($signed));
            setcookie($name, "", 1, "/", $_SERVER['HTTP_HOST'], true, false);
        }
        return $result;
    }
    public function JSONBodyToRequest() {
        if (($_SERVER['HTTP_CONTENT_TYPE'] ?? 'text/html') != 'application/json') return;
        $decodedBody = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
        if (!is_array($decodedBody)) throw new Exception('Invalid JSON payload');
        foreach($decodedBody as $key => $value) $_REQUEST[$key] = $value;
    }
}
