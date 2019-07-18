<!DOCTYPE html>
<html>
 <head>
 <style>
:root {
 background-color: #505050;
}
 body { display: none; }
 </style>
 <link href="<?= \app\Application::singleton()->url('/stylesheets/screen.css') ?>" media="screen, projection" rel="stylesheet" type="text/css" />
  <title><?= isset($tvars['title']) ? $tvars['title'] : ''  ?></title>
 </head>
 <body>
 <div id="main-menu">
<?php \sergiosgc\output\Negotiated::$singleton->template('/_/sergiosgc/menu.ul/', [
  'menu' => [
    'items' => \app\app()->getMenuItems()
  ]
]);
?>
 </div>
 <div id="content">
 <?= $tvars['content'] ?>
 </div>
 <div id="footer">&copy; 2019 &mdash; </div>
 </body>
<!-- vim: set expandtab tabstop=1 shiftwidth=1: --> 
</html>
