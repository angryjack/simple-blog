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
    catch (BaseException $e){
        $result['status'] = 'error';
        $result['text'] = $e->getMessage();
    }
    catch (PDOException $e){
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
    catch (BaseException $e){
        $result['status'] = 'error';
        $result['text'] = $e->getMessage();
    }
    catch (PDOException $e){
        $result['status'] = 'error';
        $result['text'] = $e->getMessage();
    }
    echo json_encode($result);

} else {
    $run = new InstallController();
    $run->index();
}
