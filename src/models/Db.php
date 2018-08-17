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

    private function __construct()
    {
    }

    public static function getConnection()
    {
        if (self::$_instance === null) {
            self::$_instance = self::connect();
        }
        return self::$_instance;
    }

    private static function connect(){
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
        $connection = new PDO($dsn, DB_USER, DB_PASSWORD);
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec("set names utf8");

        return $connection;
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }
}
