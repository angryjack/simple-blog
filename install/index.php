<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 18.08.2018 22:26
 */
use Angryjack\models\InstallRouter;

define('ROOT', __DIR__);
include 'autoload.php';


try {
    $installer = new InstallRouter();
    $installer->run();
} catch (Exception $e) {
    $result['status'] = 'error';
    $result['text'] = $e->getMessage();

    echo json_encode($result);
}


