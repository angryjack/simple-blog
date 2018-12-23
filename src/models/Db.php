<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 23.06.2018 22:22
 */

namespace Angryjack\models;

use Angryjack\helpers\Request;
use PDO;

/**
 * Класс для работы с базой данных
 * Class Db
 * @package Angryjack\models
 */
class Db
{
    use Request;

    protected static $instance;
    private static $params;

    private function __construct()
    {
        //
    }

    private function __clone()
    {
        //
    }

    private function __wakeup()
    {
        //
    }

    /**
     * Возвращаем экземпляр PDO
     * @return PDO
     * @throws \Exception
     */
    public static function getConnection()
    {
        if (self::$instance === null) {
            self::getParams();
            self::$instance = self::connect();
        }

        return self::$instance;
    }

    /**
     * Подключаемся к базе данных
     * @return PDO
     * @throws \Exception
     */
    private static function connect()
    {
        $dsn = "mysql:host=" . self::$params['host'] . ";dbname=" . self::$params['name'];
        $connection = new PDO($dsn, self::$params['user'], self::$params['password']);
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec("set names utf8");

        return $connection;
    }

    /**
     * Получаем данные для поключения к базе данных
     * @throws \Exception
     */
    protected static function getParams()
    {
        $paramsPath = '../includes/db_params.php';

        if (! file_exists($paramsPath)) {
            $db = Request::getData()->db;

            self::$params = array(
                'host' => $db->host,
                'user' => $db->user,
                'password' => $db->password,
                'name' => $db->name,
            );
        } else {
            self::$params = include($paramsPath);
        }
    }
}
