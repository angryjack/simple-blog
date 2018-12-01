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

        } else if (isset($data->action) && $data->action == 'install') {
            // Устанавливает сайт
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

        } else if (isset($data->action) && $data->action == 'deleteInstallDir') {
            // Удаляем папку установки
            $run = new FrontController();
            $run->deleteInstaller();
            $result['status'] = 'success';

        } else if (isset($data->action) && $data->action == 'deleteSqlTables') {
            // Удаляем таблицы из Базы данных
            $run = new FrontController();
            $run->deleteDbTables();
            $result['status'] = 'success';
            $result['text'] = 'База данных успешно очищена!';


        } else if (isset($data->action) && $data->action == 'createUser') {
            // Создаем пользователя
            $run = new FrontController();
            $run->createUser();
            $result['status'] = 'success';
            $result['text'] = 'Пользователь успешно создан!';

        } else {
            // Отображаем страницу установки
            $run = new FrontController();
            $run->index();
            return;

        }

        echo json_encode($result);
    }
}