<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:08
 */
namespace Angryjack\controllers;
use Angryjack\models\DbInstall;
use Angryjack\models\Install;

class InstallController
{
    public $data;

    public function __construct()
    {
        $this->data = self::getData();
    }

    /**
     * Получаем данные
     * @return mixed
     */
    public static function getData()
    {
        $data = file_get_contents('php://input');
        return json_decode($data);
    }

    /**
     * Форма установки сайта
     * @throws \Exception
     */
    public function index()
    {
        Install::checkDbConfAlreadyExist();

        require_once( ROOT. '/views/install.php');
    }

    /**
     * Выполняем установку сайта
     * @throws \Exception
     */
    public function install()
    {
        // проверка статуса установки сайта, вполне возможно что он уже установлен
        Install::checkDbConfAlreadyExist();

        // проверяем подлючение к базе данных
        $this->check();

        // прописываем данные от бд в конфиг
        $config = new Install($this->data->host, $this->data->user, $this->data->password, $this->data->dbname);
        $config->create();

        //делаем импорт в базу данных
        $db = new DbInstall($this->data->host, $this->data->user, $this->data->password, $this->data->dbname);
        $db->install();
    }

    /**
     * Проверяем подключение к базе данных
     */
    public function check()
    {
        new DbInstall($this->data->host, $this->data->user, $this->data->password, $this->data->dbname);
    }

    /**
     * Удаляем папку установки
     */
    public function delete(){
        $installDir = $_SERVER['DOCUMENT_ROOT'] . '/install/';
        Install::deleteInstallDir($installDir);
    }
}