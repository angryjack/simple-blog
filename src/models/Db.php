<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 22:22
 */

namespace Angryjack\models;
use PDO;

/**
 * Класс подключения к базе данных
 * Class Db
 * @package Angryjack\models
 */
class Db
{
    protected static $_instance;

    private function __construct(){}

    /**
     * Возвращаем экземпляр PDO
     * @return PDO
     * @throws \Exception
     */
    public static function getConnection()
    {
        if (self::$_instance === null) {
            self::$_instance = self::connect();
        }

        return self::$_instance;
    }

    /**
     * Подключаемся к базе данных
     * @return PDO
     * @throws \Exception
     */
    private static function connect(){

        $params = self::getParams();

        $dsn = "mysql:host={$params['host']};dbname={$params['dbname']}";
        $connection = new PDO($dsn, $params['user'], $params['password']);
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec("set names utf8");

        return $connection;
    }

    /**
     * Получаем данные для поключения к базе данных
     * @return mixed
     * @throws \Exception
     */
    protected static function getParams(){
        $paramsPath = ROOT . '/src/includes/db_params.php';

        if(!file_exists($paramsPath)){
            throw new \Exception('Не удалось загрузить конфиг Базы Данных.');
        }
        $params = include($paramsPath);

        return $params;
    }

    private function __clone(){}

    private function __wakeup(){}
}
