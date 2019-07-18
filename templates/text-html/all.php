<?php ob_start(); 
printf(<<<EOS
<p>This is a placeholder for your real homepage. <a href="/login">/login/</a> and <form method="post" action="/logout/"><input type="submit" value="logout"></form> work, with user joe and password 123.</p>
EOS
);
if (\sergiosgc\auth\AuthSingleton::getAuth()->isLoggedIn()) printf('<p>You are logged in as %s</p>', \sergiosgc\auth\AuthSingleton::getAuth()->getUser()->username); 
$tvars['title'] = _('Skeleton app ');
\sergiosgc\output\Negotiated::template('/_/layouts/default/', null, 'content');
?>