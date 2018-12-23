<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 14:10
 */

namespace Angryjack\controllers;

use Angryjack\models\User;

class AdminController extends Controller
{
    /**
     * Форма входа в админ панель
     */
    public function actionLogin()
    {
        return $this->view('admin.login');
    }

    /**
     * Метод входа в админ панель
     * throws \Exception - критические ошибки, даем на откуп нашему глобальному обработчику ошибок
     */
    public function actionDoLogin()
    {
        $data = parent::getData(false);
        $token = (new User)->login($data->login, $data->password);

        return ['token' => $token];
    }

    /**
     * Главная страница админ панели
    */
    public function actionIndex()
    {
        $token = parent::getTokenFromCookie();

        if (! parent::checkAccess($token)) {
            header('Location: /admin/login');
            exit;
        }

        return $this->view('admin.index');
    }

    /**
     * Страница управления статьями
     */
    public function actionArticle()
    {
        $token = parent::getTokenFromCookie();

        if (! parent::checkAccess($token)) {
            header('Location: /admin/login');
            exit;
        }

        return $this->view('admin.article.article');
    }

    /**
     * Страница управления категориями
     */
    public function actionCategory()
    {
        $token = parent::getTokenFromCookie();

        if (! parent::checkAccess($token)) {
            header('Location: /admin/login');
            exit;
        }

        return $this->view('admin.category.category');
    }
}
