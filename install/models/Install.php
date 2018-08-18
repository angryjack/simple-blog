<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:10
 */
namespace Angryjack\models;

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

    public function install()
    {
        self::checkDbConfAlreadyExist();

        $data = 'return array(';
        $data .= " 'host' => '" . $this->host ."', ";
        $data .= " 'dbname' => '" . $this->dbname . "', ";
        $data .= " 'user' => '" . $this->user . "', ";
        $data .= " 'password' => '" . $this->password . "' );";

        //todo обработать возможные ошибки
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/src/includes/db_params.php', $data);

    }

    public static function checkDbConfAlreadyExist()
    {
        $dbParamsPath = $_SERVER['DOCUMENT_ROOT'] . '/src/includes/db_params.php';
        if (file_exists($dbParamsPath)) {
            die('Сайт уже установлен. Удалите папку install.');
        }
    }

    public function deleteInstallDir($path){
        function rmRec($path) {
            if (is_file($path)) return unlink($path);
            if (is_dir($path)) {
                foreach(scandir($path) as $p) if (($p!='.') && ($p!='..'))
                    rmRec($path.DIRECTORY_SEPARATOR.$p);
                return rmdir($path);
            }
            return false;
        }
    }
}