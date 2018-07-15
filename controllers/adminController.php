<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 14:10
 */

/**
 * Контроллер админ панели
 * Включает в себя все экшены по изменению контента на сайте
 */
class adminController
{
    /**
     * Форма входа в админ панель
     * @return bool
     */
    public function actionLogin()
    {
        require_once(ROOT . '/views/admin/login.php');
        return true;
    }


    /**
     * Отображаем главную страницу админ панели
     * @return bool
     */
    public function actionIndex()
    {
        if(!Admin::checkAccess()){
            die(ACCESS_DENIED);
        }

        require_once(ROOT . '/views/admin/index.php');
        return true;
    }


    /**
     * Страница отображения новостей в админ панели
     * @return bool
     */
    public function actionListNews()
    {
        if(!Admin::checkAccess()){
            die(ACCESS_DENIED);
        }

        $news = News::getNewsList();
        $main = ROOT . "/views/admin/news/list_news.php";
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

    /**
     * Страница добавлния новости
     * @return bool
     */
    public function actionAddNews()
    {
        if(!Admin::checkAccess()){
            die(ACCESS_DENIED);
        }

        $main = ROOT . "/views/admin/news/add_news.php";
        $aside = ROOT . "/views/admin/news/aside_news_list.php";
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

    /**
     * Страница редактирования конкретной новости
     * Если новость не найдена - отображаем страницу со всеми новостями
     * @param $id - ID новости
     * @return bool
     */
    public function actionEditNews($id)
    {
        if(!Admin::checkAccess()){
            die(ACCESS_DENIED);
        }

        $news = News::getNewsByID($id);

        if (!$news) {
            header('Location:/admin/news');
            return true;
        }

        $main = ROOT . '/views/admin/news/edit_news.php';
        $aside = ROOT . "/views/admin/news/aside_news_list.php";
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }


    /**
     * Страница отображения категорий
     * @return bool
     */
    public function actionListCats()
    {
        if(!Admin::checkAccess()){
            die(ACCESS_DENIED);
        }

        $cats = News::getCatsList();
        $main = ROOT . "/views/admin/news/categories.php";
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

    /**
     * Страница редактирования конкретной категории
     * если категория не найдена - отображаем страницу со всеми категориями
     * @param $id - ID категории
     * @return bool
     */
    public function actionEditCategory($id)
    {
        if(!Admin::checkAccess()){
            die(ACCESS_DENIED);
        }

        $category = News::getCategoryByID($id);

        if (!$category) {
            header('Location:/admin/news/categories');
            return true;
        }

        $main = ROOT . '/views/admin/news/edit_category.php';
        require_once(ROOT . '/views/admin/index.php');
        return true;
    }

}