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
use Angryjack\exceptions\BaseException;
use Angryjack\models\Articles;


class SiteController
{
    /**
     * Отображаем главную страницу сайта
     * @return bool
     */
    public function actionIndex()
    {
        try{
            $articles = Articles::getArticles();
            $slider = ROOT . "/src/views/site/layouts/slider.php";
            $main = ROOT . "/src/views/site/article/articles.php";
        }
        catch (BaseException $e){
            $message = $e->getMessage();
        }
        require_once(ROOT . '/src/views/site/index.php');
        return true;
    }

    public function actionArticle($id)
    {
        try{
            $article = Articles::getArticle($id);
            $title = $article->title;
            $description = $article->description;
            $keywords = $article->keywords;
        }
        catch (BaseException $e){
            $message = $e->getMessage();
        }

        $slider = ROOT . "/src/views/site/layouts/slider.php";
        $main = ROOT . "/src/views/site/article/single.php";
        require_once(ROOT . '/src/views/site/index.php');
        return true;
    }

    public function actionCategory($id)
    {
        var_dump(Articles::getArticlesFromCategory($id, 1));
        die;
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