<?php
/**
 * Created by angryjack
 * Date: 2018-12-02 12:47
 */

namespace Angryjack\models;

use Angryjack\exceptions\InstallException;

class Install
{
    private $_installPath = __DIR__ . "/../includes/";

    private $_data;

    public function __construct()
    {
        $this->_data = Site::getData();
    }

    /**
     * Создаем конфиг Базы данных
     * @param $db
     * @return bool|int
     * @throws InstallException
     */
    public function createConfig()
    {

        if (file_exists($this->_installPath . '/db_params.php')) {
            throw new InstallException('Сайт уже установлен. Удалите папку install.');
        }

        if (!is_writable($this->_installPath)) {
            throw new InstallException("Папка $this->_installPath доступна только для чтения.");
        }

        $db = $this->_data->db;

        var_dump($db);
        die;

        $db_params = '<?php return array(';
        $db_params .= " 'host' => '" . $db->host . "', ";
        $db_params .= " 'name' => '" . $db->dbName . "', ";
        $db_params .= " 'user' => '" . $db->dbUser . "', ";
        $db_params .= " 'password' => '" . $db->dbPassword . "' );";

        return file_put_contents($this->_installPath . 'db_params.php', $db_params);
    }

    /**
     * Устанавливаем базу данных
     * @return bool
     * @throws \Exception
     */
    public function importDataToDb()
    {
        $sqlFile = '../includes/install.sql';

        if (!file_exists($sqlFile)) {
            throw new InstallException('Установочный SQL файл не найден!');
        }
        $db = Db::getConnection();
        $result = $db->query(file_get_contents($sqlFile));

        if (!$result) {
            throw new InstallException('Ошибка при создании базы данных.');
        }

        return true;
    }

    /**
     * Удаляем таблицы из базы данных
     * @return bool
     * @throws \Exception
     */
    public function clearDb()
    {
        $sqlFile = $this->_installPath . 'delete.sql';

        if (!file_exists($sqlFile)) {
            throw new InstallException('Установочный SQL файл не найден!');
        }
        $db = Db::getConnection();
        $result = $db->query(file_get_contents($sqlFile));

        if (!$result) {
            throw new InstallException('Произошла ошибка при удалении таблиц.');
        }

        return true;
    }

    /**
     * Создаем пользователя
     * @param $login
     * @param $password
     * @throws \Exception
     */
    public function createUser()
    {
        $login = trim($this->_data->user->login);
        $password = trim($this->_data->user->login);

        if (! empty($login)) {
            throw new InstallException('Логин не может быть пустым.');
        }
        if (! empty($password)) {
            throw new InstallException('Пароль не может быть пустым.');
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        $db = Db::getConnection();
        $sql = 'INSERT INTO users (login, passwd, role) VALUES (:login, :password, "admin")';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':login', $login, \PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, \PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new InstallException('Ошибка создания пользователя.');
        }
    }

    /**
     * Удаление конфига
     * @throws \Exception
     * @return bool - статус выполнения удаления
     */
    public function deleteDbConfigFile()
    {
        $configPath = $this->_installPath . '/db_params.php';
        if (! file_exists($configPath)){
            throw new InstallException('Файл не существует или уже был удален.');
        }

        if (! unlink($configPath)) {
            throw new InstallException('Произошла ошибка при удалении конфигурации.');
        }

        return true;
    }

    /**
     * Удаление установщика
     * @return bool
     * @throws \Exception
     */
    public function deleteInstallator() {}
}