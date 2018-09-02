<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.08.2018 13:47
 */

namespace Angryjack\models;

use Angryjack\controllers\FrontController;
use \Exception;

class InstallRouter
{

    public $data;

    public function __construct()
    {
        $data = file_get_contents('php://input');
        $this->data = json_decode($data);
    }

    /**
     * Запускаем нужное действие
     * @throws Exception
     */
    public function run()
    {
        $data = $this->data;

        // Проверяем подключение к БД
        if (isset($data->action) && $data->action == 'check') {

            $run = new FrontController();
            $run->checkDbConnection();

            $result['status'] = 'success';
            $result['text'] = 'Подключение успешно установлено!';

            // Устанавливает сайт
        } else if (isset($data->action) && $data->action == 'install') {
            try {
                $run = new FrontController();
                $run->installSite();

                $result['status'] = 'success';
                $result['text'] = 'Установка прошла успешно!';

            } catch (Exception $e) {
                // если что-то пошло не так - отменяем установку (удаляем созданный конфиг)
                $run->undoInstall();
                throw new Exception($e->getMessage());
            }

            // Удаляем папку установки
        } else if (isset($data->action) && $data->action == 'deleteInstallDir') {

            $run = new FrontController();
            $run->deleteInstaller();
            $result['status'] = 'success';

            // Удаляем таблицы из Базы данных
        } else if (isset($data->action) && $data->action == 'deleteSqlTables') {

            $run = new FrontController();
            $run->deleteDbTables();
            $result['status'] = 'success';
            $result['text'] = 'База данных успешно очищена!';

            // Создаем пользователя
        } else if (isset($data->action) && $data->action == 'createUser') {

            $run = new FrontController();
            $run->createUser();
            $result['status'] = 'success';
            $result['text'] = 'Пользователь успешно создан!';
            // Отображаем страницу установки
        } else {

            $run = new FrontController();
            $run->index();
            return;

        }

        echo json_encode($result);
    }
}