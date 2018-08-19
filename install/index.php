<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:26
 */
use Angryjack\controllers\InstallController;
use Angryjack\exceptions\BaseException;

define('ROOT', __DIR__);
include 'autoload.php';

$data = InstallController::getData();

if (isset($data->action) && $data->action == 'check') {
    try{
        $run = new InstallController();
        $run->check();
        $result['status'] = 'success';
        $result['text'] = 'Подключение успешно установлено!';
    }
    catch (Exception $e){
        $result['status'] = 'error';
        $result['text'] = $e->getMessage();
    }
    echo json_encode($result);

} else if (isset($data->action) && $data->action == 'install') {
    try{
        $run = new InstallController();
        $run->install();
        $result['status'] = 'success';
        $result['text'] = 'Установка прошла успешно!';
    }
    catch (Exception $e){
        // если что-то пошло не так - отменяем установку (удаляем созданный конфиг)
        $run->delete();

        $result['status'] = 'error';
        $result['text'] = $e->getMessage();
    }
    echo json_encode($result);

} else if (isset($data->action) && $data->action == 'delete') {
    try{
        $run = new InstallController();
        $run->delete();
        $result['status'] = 'success';
    }
    catch (Exception $e){
        $result['status'] = 'error';
        $result['text'] = $e->getMessage();
    }
    echo json_encode($result);

} else {
    try{
        $run = new InstallController();
        $run->index();
    }
    catch (Exception $e){
        echo $e->getMessage();
    }
}
