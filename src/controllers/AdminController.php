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
    public function login()
    {
        return $this->adminView('admin.login');
    }

    /**
     * Метод входа в админ панель
     * throws \Exception - критические ошибки, даем на откуп нашему глобальному обработчику ошибок
     */
    public function doLogin()
    {
        $data = parent::getData(false);
        $token = (new User)->login($data->login, $data->password);

        return ['token' => $token];
    }

    /**
     * Главная страница админ панели
    */
    public function index()
    {
        $token = parent::getTokenFromCookie();

        parent::checkAccess($token);

        return $this->adminView('admin.index');
    }

    /**
     * Страница управления статьями
     */
    public function article()
    {
        //todo назвать метод manageArticles
        $token = parent::getTokenFromCookie();

        parent::checkAccess($token);

        return $this->adminView('admin.article.article');
    }

    /**
     * Страница управления категориями
     */
    public function category()
    {
        $token = parent::getTokenFromCookie();

        parent::checkAccess($token);

        return $this->adminView('admin.category.category');
    }
}
