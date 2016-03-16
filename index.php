<?php
date_default_timezone_set ('America/New_York');
require_once 'configs0.php';
require_once 'configs.php';
require_once 'autoloader.php';
if(ENVIROMENT == 'development')
{
    error_reporting(E_ALL);
}
else
{
    error_reporting(0);
}
$routing = new Routing($_SERVER['REQUEST_URI']);
$routing->run();