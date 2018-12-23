<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 14:10
 */

namespace Angryjack\controllers;

use Angryjack\exceptions\BaseException;
use Angryjack\models\User;

class AdminController extends Controller
{
    /**
     * Форма входа в админ панель
     * @return bool
     */
    public function actionLogin()
    {
        require_once(ROOT . '/src/views/admin/login.php');
        return true;
    }

    /**
     * Метод входа в админ панель
     * throws \Exception - критические ошибки, даем на откуп нашему глобальному обработчику ошибок
     */
    public function actionDoLogin()
    {
        try {
            $data = parent::getData(false);
            $token = (new User)->login($data->login, $data->password);
            $result['status'] = 'success';
            $result['answer']['data'] = $token;

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo parent::printJson($result);
        return true;
    }

    /**
     * Главная страница админ панели
     * @return bool
     */
    public function actionIndex()
    {
        $token = parent::getTokenFromCookie();

        if (! parent::checkAccess($token)) {
            header('Location: /admin/login');

        } else {
            require_once(ROOT . '/src/views/admin/index.php');
        }

        return true;
    }

    /**
     * Страница управления статьями
     * @return bool
     */
    public function actionArticle()
    {
        $token = parent::getTokenFromCookie();

        if (! parent::checkAccess($token)) {
            header('Location: /admin/login');

        } else {
            $main = ROOT . "/src/views/admin/article/article.php";
            require_once(ROOT . '/src/views/admin/index.php');
        }

        return true;
    }

    /**
     * Страница управления категориями
     * @return bool
     */
    public function actionCategory()
    {
        $token = parent::getTokenFromCookie();

        if (! parent::checkAccess($token)) {
            header('Location: /admin/login');

        } else {
            $main = ROOT . "/src/views/admin/category/category.php";
            require_once(ROOT . '/src/views/admin/index.php');
        }

        return true;
    }
}
