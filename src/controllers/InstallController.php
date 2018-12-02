<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:08
 */
namespace Angryjack\controllers;
use Angryjack\models\Db;
use Angryjack\models\Install;

class InstallController
{

    /**
     * InstallController constructor.
     */
    public function __construct() {}

    /**
     * Форма установки сайта
     * @throws \Exception
     */
    public function actionIndex()
    {
        require_once(__DIR__ . '/../views/install/index.php');
        return true;
    }

    /**
     * Проверяем подключение к базе данных
     */
    public function actionCheckDb()
    {
        try {
            Db::getConnection();
            $result['status'] = 'success';
            $result['message'] = "Подключение успешно установлено!";
        }
        catch (\Exception $e) {
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }


    /**
     * Выполняем установку сайта
     * @throws \Exception
     */
    public function actionInit()
    {
        try {
            // проверяем подлючение к базе данных
            $this->actionCheckDb();

            // запускаем установщик
            $installer = new Install();

            // прописываем данные от бд в конфиг
            $installer->createConfig();

            //делаем импорт в базу данных
            $installer->importDataToDb();
            $result['status'] = 'success';
        }
        catch (\Exception $e){
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }


    /**
     * Создаем пользователя
     * @throws \Exception
     */
    public function actionCreateUser()
    {
        try {
            $installer = new Install();
            $installer->createUser();
            $result['status'] = 'success';
        }
        catch (\Exception $e){
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }

    /**
     * Отменяем установку
     * @throws \Exception
     */
    public function actionUndoInstall(){
        try {
            $installer = new Install();
            $installer->deleteDbConfigFile();
            $result['status'] = 'success';
        }
        catch (\Exception $e){
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }

    /**
     * Удаляем установщик после успешной установки
     * @throws \Exception
     */
    public function actionDeleteInstaller(){
        try {
            $installer = new Install();
            $installer->deleteInstallator();
            $result['status'] = 'success';
        }
        catch (\Exception $e){
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }

    /**
     * Удаляем таблицы из базы данных
     * @throws \Exception
     */
    public function actionClearDb(){
        try {
            $unInstaller = new Install();
            $unInstaller->clearDb();
            $result['status'] = 'success';
            $result['message'] = 'База данных успешно очищена!';
        }
        catch (\Exception $e){
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }
}