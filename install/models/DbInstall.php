<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:11
 */
namespace Angryjack\models;

use Angryjack\exceptions\BaseException;
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
        $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->exec("set names utf8");

        return $connection;
    }


    public function install(){

        $sqlFile = ROOT . '/includes/install.sql';

        if(!file_exists($sqlFile)){
            throw new BaseException('Файл установки базы данных не найден!');
        }
        $db = $this->connect();
        $result = $db->query(include $sqlFile);

        if(!$result){
            throw new BaseException('Ошибка при создании базы данных.');
        }

        return true;
    }
}