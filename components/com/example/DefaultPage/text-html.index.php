<?php
(function() {
    // Unpack variables in scope
    foreach (func_get_args()[0] as $veryRandomString137845ToAvoidCollisionsKey => $veryRandomString137845ToAvoidCollisionsValue) $$veryRandomString137845ToAvoidCollisionsKey = $veryRandomString137845ToAvoidCollisionsValue;
    unset($veryRandomVariableNameForThescriptFile);
    unset($veryRandomString137845ToAvoidCollisionsKey);
    unset($veryRandomString137845ToAvoidCollisionsValue);
    // Template PHP code
?><?php
$app = \app\Application::singleton();
$notifications = $app->getUserNotifications();

    // Template components
?><!DOCTYPE html><html ><?php ob_start();?><head ><?php ob_start();?><style ><?php ob_start();?>
:root {
 background-color: #505050;
}
 body { display: none; }
  <?php print(ob_get_clean()); print('</style>'); // style
?><link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png"<?php ob_start();
(function($content) { // /link
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</link>');
    }
})(ob_get_clean());?><link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png"<?php ob_start();
(function($content) { // /link
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</link>');
    }
})(ob_get_clean());?><link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png"<?php ob_start();
(function($content) { // /link
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</link>');
    }
})(ob_get_clean());?><link rel="manifest" href="/img/site.webmanifest"<?php ob_start();
(function($content) { // /link
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</link>');
    }
})(ob_get_clean());?><link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#d4262c"<?php ob_start();
(function($content) { // /link
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</link>');
    }
})(ob_get_clean());?><link rel="shortcut icon" href="/img/favicon.ico"<?php ob_start();
(function($content) { // /link
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</link>');
    }
})(ob_get_clean());?><meta name="msapplication-TileColor" content="#e0e0e0"<?php ob_start();
(function($content) { // /meta
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</meta>');
    }
})(ob_get_clean());?><meta name="msapplication-config" content="/img/browserconfig.xml"<?php ob_start();
(function($content) { // /meta
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</meta>');
    }
})(ob_get_clean());?><meta name="theme-color" content="#ffffff"<?php ob_start();
(function($content) { // /meta
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</meta>');
    }
})(ob_get_clean());?><link href="<?= strtr(@$app->url('/stylesheets/dist/screen.css'), [ '&' => '&amp;', '"' => '&quot;' ]) ?>" media="screen, projection" rel="stylesheet" type="text/css"<?php ob_start();
(function($content) { // /link
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</link>');
    }
})(ob_get_clean());?><title ><?php ob_start();print(@$_REQUEST['title']);print(ob_get_clean()); print('</title>'); // title
?><?php
 if (isset($tvars['js']) && isset($tvars['js']['head'])) foreach ($tvars['js']['head'] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
 foreach( $_REQUEST['js-head'] ?? [] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
?><script type="text/javascript" src="<?= strtr(@$app->url('/js/jsonschema-form/index.js?ver=%s', \app\app()->getConfig('application.version')), [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();print(ob_get_clean()); print('</script>'); // script
print(ob_get_clean()); print('</head>'); // head
?><body class="<?= strtr(@\implode(' ', array_merge(['minimal'], isset($_REQUEST['class']) ? [ $_REQUEST['class'] ] : [])), [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();?><div id="logo"><?php ob_start();?><a class="logo" href="/"><?php ob_start();?><img src="/img/logo.svg"<?php ob_start();
(function($content) { // /img
    if ($content == '') {
        print(' />');
    } else {
        print($content);
        print('</img>');
    }
})(ob_get_clean());print(ob_get_clean()); print('</a>'); // a
?><a class="collapse"><?php ob_start();?><?php print(ob_get_clean()); print('</a>'); // a
print(ob_get_clean()); print('</div>'); // div
?><div id="header"><?php ob_start();print(ob_get_clean()); print('</div>'); // div
?><div id="main-menu"><?php ob_start();print(ob_get_clean()); print('</div>'); // div
?><div id="content"><?php ob_start();?><h1 ><?php ob_start();print(@$_REQUEST['title']);print(ob_get_clean()); print('</h1>'); // h1
ob_start(); // com/sergiosgc/Breadcrumbs

\app\Template::componentPre(
    'com/sergiosgc/Breadcrumbs',
    [
        'breadcrumbs' => @$_REQUEST['breadcrumbs']
    ]
);

\app\Template::component(
    'com/sergiosgc/Breadcrumbs',
    [
        'content' => ob_get_clean(),
        'breadcrumbs' => @$_REQUEST['breadcrumbs']
    ]
);
?><?= $_REQUEST['content'] ?><?php print(ob_get_clean()); print('</div>'); // div
?><div id="footer"><?php ob_start();?>&copy; 2023 &mdash; Sérgio Carvalho<?php print(ob_get_clean()); print('</div>'); // div
?><?php
 if (isset($tvars['js']) && isset($tvars['js']['body'])) foreach ($tvars['js']['body'] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
 foreach( $_REQUEST['js-body'] ?? [] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
?><script src="<?= strtr(@$app->url('/js/index.js?ver=%s', \app\app()->getConfig('application.version')), [ '&' => '&amp;', '"' => '&quot;' ]) ?>"><?php ob_start();print(ob_get_clean()); print('</script>'); // script
print(ob_get_clean()); print('</body>'); // body
print(ob_get_clean()); print('</html>'); // html
})(get_defined_vars());