<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 21:38
 */

//todo закрыть прямой доступ к файлам
//todo добавить проверку на пустату ЧПУ
require_once('config.php');

use Angryjack\Models\Router;

$router = new Router();
$router->run();