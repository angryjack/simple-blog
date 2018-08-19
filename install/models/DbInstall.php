<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:11
 */
namespace Angryjack\models;

use Exception;
use \PDO;

class DbInstall
{
    public $host;
    public $user;
    public $password;
    public $dbname;

    public function __construct($host, $user, $password, $dbname)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->dbname = $dbname;

        return $this->connect();
    }


    protected function connect()
    {
        $dsn = "mysql:host=$this->host;dbname=$this->dbname";
        $connection = new PDO($dsn, $this->user, $this->password);
        //отвечает за возможность выполнять несколько sql команд через точку с запятой в одном запросе
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec("set names utf8");

        return $connection;
    }

    /**
     * Установка Базы данных
     * @return bool
     * @throws Exception
     */
    public function install()
    {
        $sqlFile = ROOT . '/includes/install.sql';

        if(!file_exists($sqlFile)){
            throw new Exception('Файл установки базы данных не найден!');
        }
        $db = $this->connect();
        $result = $db->query(file_get_contents($sqlFile));

        if(!$result){
            throw new Exception('Ошибка при создании базы данных.');
        }

        return true;
    }
}