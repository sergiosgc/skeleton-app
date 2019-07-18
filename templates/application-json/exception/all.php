<?php 
if (!isset($tvars['exception'])) throw new Exception('Exception handling template called with no exception');
$result = [
    'success' => false,
    'error' => 'Unexpected exception caught in top level',
];
try {
    if (\app\Application::singleton()->getConfigWithDefault('development', false)) {
        $ex = $tvars['exception'];
        $result['exception'] = [];
        $result['exception']['message'] = $ex->getMessage();
        $result['exception']['class'] = get_class($ex);
        $result['exception']['code'] = $ex->getCode();
        $result['exception']['traceback'] = [ [
            'line' => $ex->getLine(),
            'file' => $ex->getFile(),
        ]];
        foreach ($ex->getTrace() as $idx => $tb) $result['exception']['traceback'][] = $tb;
    }
} catch (Exception $e) {
    $ex = $tvars['exception'];
    $result['exception'] = [];
    $result['exception']['message'] = $ex->getMessage();
    $result['exception']['class'] = get_class($ex);
    $result['exception']['code'] = $ex->getCode();
    $result['exception']['traceback'] = [ [
        'line' => $ex->getLine(),
        'file' => $ex->getFile(),
    ]];
    foreach ($ex->getTrace() as $idx => $tb) $result['exception']['traceback'][] = $tb;
}

print(json_encode($result));
