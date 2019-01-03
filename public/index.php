<?php

use Angryjack\models\Router;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../src/bootstrap.php';

$router = new Router();
$router->run();
