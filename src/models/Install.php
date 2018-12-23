<?php
/**
 * Created by angryjack
 * Date: 2018-12-02 12:47
 */

namespace Angryjack\models;

use Angryjack\exceptions\InstallException;
use Angryjack\helpers\Request;

class Install
{
    use Request;

    private $installPath = __DIR__ . '/../includes/';

    private $data;

    public function __construct()
    {
        $this->data = Request::getData();
    }

    /**
     * Создаем конфиг Базы данных
     * @return bool|int
     * @throws InstallException
     */
    public function createConfig()
    {

        if (file_exists($this->installPath . '/db_params.php')) {
            throw new InstallException('Сайт уже установлен. Удалите папку install.');
        }

        if (!is_writable($this->installPath)) {
            throw new InstallException("Папка $this->installPath доступна только для чтения.");
        }

        $db = $this->data->db;

        $db_params = '<?php return array(';
        $db_params .= " 'host' => '" . $db->host . "', ";
        $db_params .= " 'name' => '" . $db->name . "', ";
        $db_params .= " 'user' => '" . $db->user . "', ";
        $db_params .= " 'password' => '" . $db->password . "' );";

        return file_put_contents($this->installPath . 'db_params.php', $db_params);
    }

    /**
     * Устанавливаем базу данных
     * @return bool
     * @throws \Exception
     */
    public function importDataToDb()
    {
        $sqlFile = $this->installPath . 'install.sql';

        if (! file_exists($sqlFile)) {
            throw new InstallException('Установочный SQL файл не найден!');
        }
        $db = Db::getConnection();
        $result = $db->query(file_get_contents($sqlFile));

        if (! $result) {
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
        $sqlFile = $this->installPath . 'delete.sql';

        if (! file_exists($sqlFile)) {
            throw new InstallException('Установочный SQL файл не найден!');
        }
        $db = Db::getConnection();
        $result = $db->query(file_get_contents($sqlFile));

        if (! $result) {
            throw new InstallException('Произошла ошибка при удалении таблиц.');
        }

        return true;
    }

    /**
     * Создаем пользователя
     * @throws \Exception
     */
    public function createUser()
    {
        $login = trim($this->data->user->login);
        $password = trim($this->data->user->login);

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
        return true;
    }

    /**
     * Удаление конфига
     * @throws \Exception
     * @return bool - статус выполнения удаления
     */
    public function deleteDbConfigFile()
    {
        $configPath = $this->installPath . 'db_params.php';
        if (! file_exists($configPath)) {
            throw new InstallException('Файл не существует или уже был удален.');
        }

        if (! unlink($configPath)) {
            throw new InstallException('Произошла ошибка при удалении конфигурации.');
        }

        return true;
    }

    /**
     * Удаление установщика
     */
    public function deleteInstallator()
    {
        return false;
    }
}
