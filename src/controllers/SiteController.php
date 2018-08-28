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
use Angryjack\models\Categories;


class SiteController
{
    /**
     * Отображаем главную страницу сайта
     * @return bool
     */
    public function actionIndex()
    {
    }

    /**
     * Отображение конкретной статьи
     * @param $id
     * @return bool
     */
    public function actionArticle($id)
    {
        try {
            $articleManager = new Articles();
            $article = $articleManager->getArticle($id);
            $title = $article->title;
            $description = $article->description;
            $keywords = $article->keywords;
        }
        catch (BaseException $e){
            $message = $e->getMessage();
        }

        $slider = ROOT . "/src/views/site/layouts/slider.php";
        $content = ROOT . "/src/views/site/article/article.php";
        require_once(ROOT . '/src/views/site/index.php');
        return true;
    }

    /**
     * Отображение конкретной категории
     * @param $id
     * @param int $page
     * @return bool
     */
    public function actionCategory($id = false, $page = 1)
    {
        try {
            //если нет категории, то это главная страница
            if($id){
                $categoryManager = new Categories();
                $category = $categoryManager->getCategory($id);
                $title = "Категория: $category->title";
                $description = $category->description;
                $keywords = $category->keywords;
            }
            $articleManager = new Articles();
            $articles = $articleManager->getArticles($id, $page);

        }
        catch (BaseException $e){
            $message = $e->getMessage();
        }

        $slider = ROOT . "/src/views/site/layouts/slider.php";
        $content = ROOT . "/src/views/site/category/category.php";
        require_once(ROOT . '/src/views/site/index.php');
        return true;
    }

}