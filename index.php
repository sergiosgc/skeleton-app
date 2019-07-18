<?php
ini_set('max_execution_time', 300);
require_once('vendor/autoload.php');

\sergiosgc\output\NegotiatedErrorHandler::setup();
sergiosgc\crud\RelationalFormOptionFetcher::register();
\app\Application::singleton();

(new \sergiosgc\router\Rest('rest'))->route();
if (!isset($tvars)) throw new Exception('Request resulted in no $tvars set');
if (!array_key_exists('result', $tvars)) throw new Exception('Request resulted in $tvars[\'result\'] not being set');

if (array_key_exists('success', $tvars['result']) && !array_key_exists('data', $tvars['result'])) $tvars['result']['data'] = null; // Fill in missing data value
if (!array_key_exists('success', $tvars['result']) && array_key_exists('data', $tvars['result'])) $tvars['success'] = true; // Fill in missing success value
if (!array_key_exists('success', $tvars['result'])) $tvars = [ 'success' => true, 'data' => $tvars ]; // Normalize shortcut result

(new \sergiosgc\output\Negotiated('templates', array('application/json; charset=UTF-8', 'text/html; charset=UTF-8')))->template();
