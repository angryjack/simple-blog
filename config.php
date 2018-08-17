<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

define('DB_HOST', 'localhost');
define('DB_NAME', 'site');
define('DB_USER', 'test');
define('DB_PASSWORD', 'test');
define('ROOT', __DIR__);

spl_autoload_register(function ($class) {

    // project-specific namespace prefix
    $prefix = 'Angryjack\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

set_exception_handler(function (\Throwable $e) {
    //file_put_contents('errors.txt', $e->getMessage() . PHP_EOL);
    echo $e->getMessage();
    echo $e->getFile();
    echo $e->getLine();
    //header('HTTP/1.1 503 Service Temporarily Unavailable');
    //header('Status: 503 Service Temporarily Unavailable');
    //header('Retry-After: 300');
    //include 'src/views/site/error.php';
});