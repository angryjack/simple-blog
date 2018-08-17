<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 14:10
 */

/**
 * Контроллер пользовательской части сайта
 */
namespace Angryjack\controllers;
use Angryjack\models\Articles;


class siteController
{
    /**
     * Отображаем главную страницу сайта
     * @return bool
     */
    public function actionIndex()
    {
        $slider = ROOT . "/src/views/site/layouts/slider.php";
        $main = ROOT . "/src/views/site/article/articles.php";
        require_once(ROOT . '/src/views/site/index.php');
        return true;
    }

    /**
     * Страница со статьей
     * @return bool
     */
    public function actionArticle($id)
    {
        $article = Articles::getArticle($id);

        $title = $article->title;
        $description = $article->description;
        $keywords = $article->keywords;

        $slider = ROOT . "/src/views/site/layouts/slider.php";
        $main = ROOT . "/src/views/site/article/single.php";
        require_once(ROOT . '/src/views/site/index.php');
        return true;
    }

    /**
     * Страница с категорией
     * @return bool
     */
    public function actionCategory()
    {
        $category = null;
        $page = null;
        $articles = Articles::getArticlesFromCategory($category, $page);

        $title = $articles["answer"]["data"]->title;
        $description = $articles["answer"]["data"]->description;
        $keywords = $articles["answer"]["data"]->keywords;

        $slider = ROOT . "/src/views/site/layouts/slider.php";
        $main = ROOT . "/src/views/site/category/category.php";
        require_once(ROOT . '/src/views/site/index.php');
        return true;
    }

}