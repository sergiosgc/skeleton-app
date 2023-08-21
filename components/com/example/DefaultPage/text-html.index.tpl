<?php
$app = \app\Application::singleton();
$notifications = $app->getUserNotifications();
?>
----
<![CDATA[<!DOCTYPE html>]]>
<html>
 <head>
  <style>
:root {
 background-color: #505050;
}
 body { display: none; }
  </style>
  <link rel="apple-touch-icon" sizes="180x180" href="/img/apple-touch-icon.png" />
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png" />
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png" />
  <link rel="manifest" href="/img/site.webmanifest" />
  <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#d4262c" />
  <link rel="shortcut icon" href="/img/favicon.ico" />
  <meta name="msapplication-TileColor" content="#e0e0e0" />
  <meta name="msapplication-config" content="/img/browserconfig.xml" />
  <meta name="theme-color" content="#ffffff" />
  <link href="$app->url('/stylesheets/dist/screen.css')" media="screen, projection" rel="stylesheet" type="text/css" />
  <title>$_REQUEST['title']</title>
<![CDATA[<?php
 if (isset($tvars['js']) && isset($tvars['js']['head'])) foreach ($tvars['js']['head'] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
 foreach( $_REQUEST['js-head'] ?? [] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
?>]]>
  <script type="text/javascript" src="$app->url('/js/jsonschema-form/index.js?ver=%s', \app\app()->getConfig('application.version'))"></script>
 </head>
 <body class="\implode(' ', array_merge(['minimal'], isset($_REQUEST['class']) ? [ $_REQUEST['class'] ] : []))">
  <div id="logo">
   <a class="logo" href="/"><img src="/img/logo.svg" /></a>
   <a class="collapse"></a>
  </div>
  <div id="header">
  </div>
  <div id="main-menu">
  </div>
  <div id="content">
   <h1>$_REQUEST['title']</h1>
   <com.sergiosgc.breadcrumbs breadcrumbs="$_REQUEST['breadcrumbs']" />
<![CDATA[<?= $_REQUEST['content'] ?>]]>
 </div>
 <div id="footer"><![CDATA[&copy; 2023 &mdash; Sérgio Carvalho]]></div>
<![CDATA[<?php
 if (isset($tvars['js']) && isset($tvars['js']['body'])) foreach ($tvars['js']['body'] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
 foreach( $_REQUEST['js-body'] ?? [] as $src) printf('<script type="text/javascript" src="%s"></script>', $src);
?>]]>

  <script src="$app->url('/js/index.js?ver=%s', \app\app()->getConfig('application.version'))"></script>
 </body>
<!-- vim: set expandtab tabstop=1 shiftwidth=1: --> 
</html>