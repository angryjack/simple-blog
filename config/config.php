<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'site');
define('DB_USER', 'test');
define('DB_PASSWORD', 'test');
define('ROOT', $_SERVER['DOCUMENT_ROOT']);

set_include_path(get_include_path()
    . PATH_SEPARATOR . ROOT . '/controllers'
    . PATH_SEPARATOR . ROOT . '/models');
spl_autoload_register(function ($class) {
    include_once  $class . '.php';
});

include_once('lang.php');