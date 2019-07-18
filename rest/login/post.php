<?php
global $tvars;
try {
    \sergiosgc\auth\AuthSingleton::getAuth()->login($_REQUEST);
    $tvars = [ 'result' => [ 'token' => \sergiosgc\auth\AuthSingleton::getAuth()->token ]];
} catch (\sergiosgc\auth\AuthenticationException $e) {
    $tvars = ['result' => [
        'success' => false,
        'data' => [
            'errors' => [
                'username' => _('Incorrect username or password'),
                'password' => _('Incorrect username or password')
            ]
        ]
    ]];
}