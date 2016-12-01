<?php
/**
*入口文件
*/
define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
date_default_timezone_set('Asia/Shanghai');
error_reporting(E_ALL);
$config = S_ROOT.'config/config.php';
require S_ROOT.'system/core/runtime.php';
Tem::createWebApplication($config)->run();