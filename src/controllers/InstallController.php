<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:08
 */
namespace Angryjack\controllers;

use Angryjack\models\Db;
use Angryjack\models\Install;

class InstallController extends Controller
{
    /**
     * @var Install instance of Installer
     */
    protected $instance;

    /**
     * InstallController constructor.
     */
    public function __construct()
    {
        if (empty($this->instance)) {
            $this->instance = new Install();
        }
        return $this->instance;
    }

    /**
     * Форма установки сайта
     * @throws \Exception
     */
    public function actionIndex()
    {
        return $this->view('install.index');
    }

    /**
     * Проверяем подключение к базе данных
     * @return bool
     * @throws \Exception
     */
    public function actionCheckDb() : bool
    {
        return (Db::getConnection()) ? true : false;
    }

    /**
     * Выполняем установку сайта
     * @throws \Exception
     */
    public function actionInit() : bool
    {
        $this->actionCheckDb();

        $this->instance->createConfig();
        $this->instance->importDataToDb();

        return true;
    }

    /**
     * Создаем пользователя
     * @throws \Exception
     */
    public function actionCreateUser() : bool
    {
        return $this->instance->createUser();
    }

    /**
     * Отменяем установку
     * @throws \Exception
     */
    public function actionUndoInstall() : bool
    {
        return $this->instance->deleteDbConfigFile();
    }

    /**
     * Удаляем установщик после успешной установки
     * @throws \Exception
     */
    public function actionDeleteInstaller() : bool
    {
        return $this->instance->deleteInstallator();
    }

    /**
     * Удаляем таблицы из базы данных
     * @throws \Exception
     */
    public function actionClearDb() : bool
    {
        return $this->instance->clearDb();
    }
}
