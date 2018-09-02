<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:10
 */
namespace Angryjack\models;

use Exception;

class File
{
    /**
     * Проверяем существует ли конфиг БД
     */
    public function checkDbConfigExist()
    {
        $dbParamsPath = $_SERVER['DOCUMENT_ROOT'] . '/src/includes/db_params.php';
        if (file_exists($dbParamsPath)) {
            die('Сайт уже установлен. Удалите папку install.');
        }
    }

    /**
     * Создаем конфиг БД
     * @param $db
     * @throws Exception
     */
    public function createDbConfigFile($db)
    {
        $this->checkDbConfigExist();

        $installPath = $_SERVER['DOCUMENT_ROOT'] . '/src/includes/';

        if(!is_writable($installPath)){
            throw new Exception("Папка $installPath доступна только для чтения.");
        }

        $db_params = '<?php return array(';
        $db_params .= " 'host' => '" . $db->host ."', ";
        $db_params .= " 'dbname' => '" . $db->dbName . "', ";
        $db_params .= " 'user' => '" . $db->dbUser . "', ";
        $db_params .= " 'password' => '" . $db->dbPassword . "' );";

        file_put_contents($installPath . 'db_params.php', $db_params);
    }

    /**
     * Удаление конфига
     * @throws Exception
     */
    public function deleteDbConfigFile()
    {
        $dbParamsPath = $_SERVER['DOCUMENT_ROOT'] . '/src/includes/db_params.php';
        if (file_exists($dbParamsPath)) {
            if(!unlink($dbParamsPath)){
                throw new Exception('Произошла ошибка при удалении конфигурации.');
            }
        }
    }

    /**
     * Удаление папки установки
     * @param $path
     * @return bool
     * @throws Exception
     */
    public function deleteInstallDir($path){
            if (is_file($path)) return unlink($path);
            if (is_dir($path)) {
                foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                    self::deleteInstallDir($path.DIRECTORY_SEPARATOR.$p);
                return rmdir($path);
            }
            throw new Exception("Произошла ошибка при удалении $path");
    }

}