<?php

define('ROOT', __DIR__);

set_exception_handler(function (\Throwable $e) {
    file_put_contents('errors.txt', $e->getMessage() . PHP_EOL);
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    header('Status: 503 Service Temporarily Unavailable');
    header('Retry-After: 300');
    include 'src/views/site/error.php';
});
