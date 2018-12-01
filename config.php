<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
define('ROOT', __DIR__);


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