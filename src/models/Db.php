<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 22:22
 */

namespace Angryjack\models;
use PDO;

class Db
{
    protected static $_instance;

    private function __construct(){}

    public static function getConnection()
    {
        if (self::$_instance === null) {
            self::$_instance = self::connect();
        }

        return self::$_instance;
    }

    private static function connect(){

        $paramsPath = ROOT . '/src/includes/db_params.php';
        $params = include($paramsPath);

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $connection = new PDO($dsn, $params['user'], $params['password']);
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec("set names utf8");

        return $connection;
    }

    private function __clone(){}

    private function __wakeup(){}
}
