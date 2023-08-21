<?php
header('Content-type: application/json');
$to_encode = get_defined_vars();
foreach(array_keys($to_encode) as $key) if (
    $key[0] == '_' ||
    substr($key, 0, strlen('veryRandom')) == 'veryRandom' ||
    $key == 'debug'
) unset($to_encode[$key]);
$to_encode = [ 'success' => true, 'data' => $to_encode ];
if (isset($to_encode['data']['success'])) {
    $to_encode['success'] = (bool) $to_encode['data']['success'];
    unset($to_encode['data']['sucess']);
}
print(json_encode($to_encode));
