<?php
ob_start();
$form = new \sergiosgc\form\Form([
    'title' => _('Login'),
    'action' => \app\app()->url('/login'),
    'method' => 'POST',
    'properties' => [
        'username' => [
            'type' => 'text',
            'validation' => [ 'required', 'nonempty' ],
            'title' => _('Username')
        ],
        'password' => [
            'type' => 'password',
            'validation' => [ 'required', 'nonempty' ],
            'title' => _('Password')
        ],
        'submit' => [
            'type' => 'submit',
            'title' => _('Login')
        ]
    ]
]);
$form->setValues($_REQUEST);
if (array_key_exists('errors', $tvars['result']['data'])) $form->setErrors($tvars['result']['data']['errors']);
$form->output();
$tvars['title'] = _('Login');
\sergiosgc\output\Negotiated::template('/_/layouts/default/', $tvars, 'content');