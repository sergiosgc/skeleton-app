<?php
(function() {
    // Unpack variables in scope
    foreach (func_get_args()[0] as $veryRandomString137845ToAvoidCollisionsKey => $veryRandomString137845ToAvoidCollisionsValue) $$veryRandomString137845ToAvoidCollisionsKey = $veryRandomString137845ToAvoidCollisionsValue;
    unset($veryRandomVariableNameForThescriptFile);
    unset($veryRandomString137845ToAvoidCollisionsKey);
    unset($veryRandomString137845ToAvoidCollisionsValue);
    // Template PHP code
?><?php
$app = \app\app();

    // Template components
ob_start(); // com/example/DefaultPage

\app\Template::componentPre(
    'com/example/DefaultPage',
    [
        'title' => __('Dashboard')
    ]
);
?><p ><?php ob_start();?>Hello World<?php print(__(ob_get_clean())); print('</p>'); // p

\app\Template::component(
    'com/example/DefaultPage',
    [
        'content' => ob_get_clean(),
        'title' => __('Dashboard')
    ]
);
})(get_defined_vars());