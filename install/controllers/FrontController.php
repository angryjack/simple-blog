<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:08
 */
namespace Angryjack\controllers;
use Angryjack\models\Query;
use Angryjack\models\File;

class FrontController
{
    public $data;
    public $installDir;

    /**
     * FrontController constructor.
     * Получаем данные и папку установки
     */
    public function __construct()
    {
        $data = file_get_contents('php://input');
        $this->data = json_decode($data);
        $this->installDir = $_SERVER['DOCUMENT_ROOT'] . '/install/';
    }

    /**
     * Форма установки сайта
     * @throws \Exception
     */
    public function index()
    {
        $installer = new File();
        $installer->checkDbConfigExist();

        require_once( ROOT. '/views/install.php');
    }

    /**
     * Выполняем установку сайта
     * @throws \Exception
     */
    public function installSite()
    {
        // проверка статуса установки сайта, вполне возможно что он уже установлен
        $installer = new File();
        $installer->checkDbConfigExist();

        // проверяем подлючение к базе данных
        $this->checkDbConnection();

        // прописываем данные от бд в конфиг
        $installer->createDbConfigFile($this->data);

        //делаем импорт в базу данных
        $db = new Query($this->data);
        $db->createDb();
    }

    /**
     * Проверяем подключение к базе данных
     */
    public function checkDbConnection()
    {
        $db = new Query($this->data);
        $db->connect();
    }

    /**
     * Создаем пользователя
     * @throws \Exception
     */
    public function createUser()
    {
        $login = $this->data->userLogin;
        $password = $this->data->userPassword;

        $installer = new Query($this->data);
        $installer->createUser($login, $password);
    }

    /**
     * Отменяем установку
     * @throws \Exception
     */
    public function undoInstall(){

        $unInstaller = new File();
        $unInstaller->deleteDbConfigFile();
    }

    /**
     * Удаляем установщик после успешной установки
     * @throws \Exception
     */
    public function deleteInstaller(){

        $installer = new File();
        $installer->deleteInstallDir($this->installDir);
    }

    /**
     * Удаляем таблицы из базы данных
     * @throws \Exception
     */
    public function deleteDbTables(){
        $unInstaller = new Query($this->data);
        $unInstaller->deleteTables();

    }
}