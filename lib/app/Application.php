<?php
namespace app;

class Application {
    use \sergiosgc\config\Config;
    public $config = null;
    public $paths = [];
    private static $_singleton = null;
    public function __construct() {
        $this->paths['root'] = realpath(__DIR__ . '/../..');
        $this->paths['config'] = realpath($this->paths['root'] . '/config');
        $this->paths['templates'] = realpath($this->paths['root'] . '/templates');
        foreach ($this->paths as $key => $value) if ($value === false) throw new Exception(sprintf('Path %s does not exist', $key));
        self::$_singleton = $this;
        \sergiosgc\auth\AuthSingleton::getAuth(
            ['\model\User', 'checkCredentials'], 
            function ($id) { return \model\User::dbRead('id = ?', $id); }, 
            'da39a3ee5e6b4b0d3255bfef95601890afd80709'
        );
        openlog("applog", LOG_PID | LOG_PERROR, LOG_LOCAL0);
    }
    public static function singleton() {
        if (!static::$_singleton) static::$_singleton = new Application();
        return static::$_singleton;
    }
    public function getDatabaseConnection() {
        static $dbConnection = null;
        if (is_null($dbConnection)) {
            $dsn = sprintf('sqlite:%s/private/database.sqlite', $this->paths['root']);
            $dbConnection = new \PDO($dsn);
            $dbConnection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $dbConnection;
    }
    public function url($url) {
        return preg_replace('_^/_', $this->getConfigWithDefault('root_uri', '/'), $url);
    }
    public function redirect($url) {
        $args = func_get_args();
        if (count($args) > 1) $url = call_user_func_array(function_exists('\sergiosgc\sprintf') ? '\sergiosgc\sprintf' : 'sprintf', $args);
        header('Location: ' . $url);
        exit;
    }
    public static function uriToCSSClass($uri) {
        $parts = explode('/', $uri);
        $result = ['root'];
        $soFar = 'root';
        foreach ($parts as $part) {
            if ($part == "") continue;
            $soFar = '-' . $part;
            $result[] = $soFar;
        }
        $result[] = $result[count($result) - 1] . '-end';
        return $result;
    }
    private function _findActiveHrefInMenu($menu, $uri) {
        $bestHref = '';
        foreach ($menu as $index => $item) {
            if (isset($item['submenu']) && $item['submenu']) {
                $candidate = $this->_findActiveHrefInMenu($item['submenu'], $uri);
                $bestHref = strlen($candidate) > strlen($bestHref) ? $candidate : $bestHref;
            }
            if (!isset($item['href']) || !$item['href']) continue;
            if (substr($uri, 0, strlen($item['href'])) == $item['href']) $bestHref = strlen($item['href']) > strlen($bestHref) ? $item['href'] : $bestHref;
        }
        return $bestHref;
    }
    public function setActiveMenu($menu, $uri, $activeHref = null) {
        $returnMenuOnly = is_null($activeHref);
        if (is_null($activeHref)) $activeHref = $this->_findActiveHrefInMenu($menu, $uri);
        $menuHasActive = false;
        foreach ($menu as $index => $item) {
            $hasActiveSubmenu = false;
            if (isset($item['submenu']) && $item['submenu']) list($menu[$index]['submenu'], $hasActiveSubmenu) = $this->setActiveMenu($item['submenu'], $uri, $activeHref);
            $menu[$index]['active'] = ($hasActiveSubmenu || isset($item['href']) && $item['href'] == $activeHref);
            $menuHasActive = $menuHasActive || $menu[$index]['active'];
        }
        return $returnMenuOnly ? $menu : [ $menu, $menuHasActive ];
    }
    public function getMenuItems() {
        $result = [
            [
                'href' => '/',
                'label' => _('Home')
            ],
        ];
        $result = $this->setActiveMenu($result, $_SERVER['REQUEST_URI']);
        return $result;
    }
    public function getMenu($menu = null, $classPrepend = 'menu-') {
        $result = $menu ?: $this->getMenuItems();
        foreach ($result as $index => $item) {
            if (isset($item['submenu'])) $result[$index]['submenu'] = $this->getMenu($item['submenu'], $classPrepend);
            if (!isset($item['class'])) {
                $classes = $this->uriToCSSClass($item['href']);
                $result[$index]['class'] = $classPrepend . $classes[count($classes)-2];
            }
        }
        return $result;
    }
}