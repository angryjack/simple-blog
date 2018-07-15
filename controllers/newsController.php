<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 14.07.2018 12:58
 */

/**
 * Class newsController контроллер для отображения новостей
 */
class newsController
{
    /**
     * Отображаем главную страницу новостей
     * @return bool
     */
    public function actionIndex()
    {
        $news = News::getNewsList();

        //title, description and keywords
        $headerTitle = '';
        $headerDescription = '';
        $headerKeywords = '';


        $main = ROOT . '/views/site/news/news_list.php';

        require_once(ROOT . '/views/site/index.php');
        return true;

    }
}