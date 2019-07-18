<?php
global $tvars;
\sergiosgc\auth\AuthSingleton::getAuth()->logout();
$tvars = ['result' => [ 'success' => true ]];

