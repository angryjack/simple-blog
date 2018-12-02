<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 22:22
 */

namespace Angryjack\models;
use PDO;

/**
 * Класс для работы с базой данных
 * Class Db
 * @package Angryjack\models
 */
class Db
{
    protected static $_instance;
    private static $_params;

    private function __construct(){}
    private function __clone(){}
    private function __wakeup(){}

    /**
     * Возвращаем экземпляр PDO
     * @return PDO
     * @throws \Exception
     */
    public static function getConnection()
    {
        if (self::$_instance === null) {
            self::getParams();
            self::$_instance = self::connect();
        }

        return self::$_instance;
    }

    /**
     * Подключаемся к базе данных
     * @return PDO
     * @throws \Exception
     */
    private static function connect()
    {
        $dsn = "mysql:host=" . self::$_params['host'] . ";dbname=" . self::$_params['name'];
        $connection = new PDO($dsn, self::$_params['user'], self::$_params['password']);
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
        $paramsPath = '../includes/db_params.php';

        if (! file_exists($paramsPath)){
            $db = Site::getData()->db;

            self::$_params = array(
                'host' => $db->host,
                'user' => $db->user,
                'password' => $db->password,
                'name' => $db->name,
            );
        } else {
            self::$_params = include($paramsPath);
        }

    }
}
