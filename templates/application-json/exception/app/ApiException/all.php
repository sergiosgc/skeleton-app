<?php 
if (!isset($tvars['exception'])) throw new Exception('Exception handling template called with no exception');
$result = [
    'success' => false,
    'error' => [ 
        'message' => $tvars['exception']->getMessage(),
        'class' => get_class($tvars['exception']),
    ]
];
print(json_encode($result));

