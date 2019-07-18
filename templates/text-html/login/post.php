<?php
global $tvars;
if ($tvars['result']['success']) {
    \sergiosgc\auth\AuthSingleton::getAuth()->sendCookie();
    \app\app()->redirect(
        array_key_exists('forward_url', $_REQUEST) ? 
            $_REQUEST['forward_url'] :
            '/'
    );
} else {
    include(__DIR__ . '/get.php');
}