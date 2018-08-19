<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:10
 */
namespace Angryjack\models;

use Exception;

class Install
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
    }

    /**
     * Запись параметров Базы данных в файл
     * @throws Exception
     */
    public function create()
    {
        self::checkDbConfAlreadyExist();

        $db_params = '<?php return array(';
        $db_params .= " 'host' => '" . $this->host ."', ";
        $db_params .= " 'dbname' => '" . $this->dbname . "', ";
        $db_params .= " 'user' => '" . $this->user . "', ";
        $db_params .= " 'password' => '" . $this->password . "' );";

        $installPath = $_SERVER['DOCUMENT_ROOT'] . '/src/includes/';


        if(!is_writable($installPath)){
            throw new Exception("Папка $installPath доступна только для чтения.");
        }

        file_put_contents($installPath . 'db_params.php', $db_params);
    }

    /**
     * Удаление конфига
     * @throws Exception
     */
    public function delete()
    {
        $dbParamsPath = $_SERVER['DOCUMENT_ROOT'] . '/src/includes/db_params.php';
        if(!unlink($dbParamsPath)){
            throw new Exception('Произошла ошибка при удалении конфигурации.');
        }
    }

    /**
     * Проверяем установлен ли сайт
     * @throws Exception
     */
    public static function checkDbConfAlreadyExist()
    {
        $dbParamsPath = $_SERVER['DOCUMENT_ROOT'] . '/src/includes/db_params.php';
        if (file_exists($dbParamsPath)) {
            throw new Exception('Сайт уже установлен. Удалите папку install.');
        }
    }

    /**
     * Удаление папки установки
     * @param $path
     * @return bool
     * @throws Exception
     */
    public static function deleteInstallDir($path){
            if (is_file($path)) return unlink($path);
            if (is_dir($path)) {
                foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                    self::deleteInstallDir($path.DIRECTORY_SEPARATOR.$p);
                return rmdir($path);
            }
            throw new Exception("Произошла ошибка при удалении $path");
    }
}