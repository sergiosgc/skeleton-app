<?php
ini_set('max_execution_time', 300);
ini_set('display_errors', 'on');
require_once('vendor/autoload.php');
//try {
    \app\Application::singleton()->getRouter()->output();
/*
} catch (\sergiosgc\auth\NotLoggedInException $e) {
    while(ob_get_level()) ob_end_clean();
    \app\Template::component('com/sooma/duo/NotLoggedInException', [ 'exception' => $e, 'development' => true ]);
} catch (Error $e) {
    while(ob_get_level()) ob_end_clean();
    \app\Template::component('com/sooma/duo/Exception', [ 'exception' => $e, 'development' => true ]);
} catch (Exception $e) {
    while(ob_get_level()) ob_end_clean();
    \app\Template::component('com/sooma/duo/Exception', [ 'exception' => $e, 'development' => true ]);
}
*/