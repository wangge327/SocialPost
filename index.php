<?php
if (isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR']=='192.168.0.54' || $_SERVER['REMOTE_ADDR']=='192.168.0.53' ||   $_SERVER['REMOTE_ADDR']=='45.135.186.3' || $_SERVER['REMOTE_ADDR']=='192.168.1.234'))
    ini_set('display_errors', 'On');
else
    ini_set('display_errors', 'Off');
error_reporting(E_ERROR | E_WARNING | E_PARSE);

include_once 'vendor/autoload.php';

use Simcify\Application;
$app = new Application();
$app->route();

