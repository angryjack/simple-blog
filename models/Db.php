<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 22:22
 */

//todo вынести в папку components

class Db
{
    private static $instance;
    private $db;

    public static function getConnection()
    {
        if (self::$instance == null){
            self::$instance = new Db();
        }

        return self::$instance;
    }

    private function __construct()
    {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
            $db = new PDO($dsn, DB_USER, DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->exec("set names utf8");
            return $db;
        } catch (Exception $e) {
            die(ERROR_DATABASE_CONNECTION);
            //todo логирование ошибки в файл
            //var_dump($e->getMessage());
        }
    }
}
