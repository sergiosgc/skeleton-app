<?php
namespace app;
function app(): Application { return Application::singleton(); }
function urlbase64_encode(string $toEncode): string { return urlencode(base64_encode($toEncode)); }
function urlbase64_decode(string $toDecode): string { return base64_decode(urldecode($toDecode)); }