<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:11
 */
namespace Angryjack\models;
use Exception;
use \PDO;

class Query
{
    public $host;
    public $user;
    public $password;
    public $dbname;

    public function __construct($db)
    {
        $this->host = $db->host;
        $this->user = $db->dbUser;
        $this->password = $db->dbPassword;
        $this->dbname = $db->dbName;
    }

    public function connect()
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
    public function createDb()
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

    /**
     * Удаляем таблицы из базы данных
     * @return bool
     * @throws Exception
     */
    public function deleteTables(){
        $sqlFile = ROOT . '/includes/delete.sql';

        if(!file_exists($sqlFile)){
            throw new Exception('SQL файл не найден!');
        }
        $db = $this->connect();
        $result = $db->query(file_get_contents($sqlFile));

        if(!$result){
            throw new Exception('Произошла ошибка при удалении таблиц.');
        }

        return true;
    }

    /**
     * Создаем пользователя
     * @param $login
     * @param $password
     * @throws Exception
     */
    public function createUser($login, $password)
    {
        if(!trim($login)){
            throw new Exception('Логин не может быть пустым.');
        }
        if(!trim($password)){
            throw new Exception('Пароль не может быть пустым.');
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $db = $this->connect();
        $sql = 'INSERT INTO users (login, passwd, role) VALUES (:login, :password, "admin")';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':login', $login, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        if(!$stmt->execute()){
            throw new Exception('Ошибка создания пользователя.');
        }
    }


}