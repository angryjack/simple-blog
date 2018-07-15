<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 08.07.2018 23:31
 */

/**
 * Класс отрисовки контента
 * В будущем будет заменен на js фреймворк,
 * который сам будет заниматься отрисовкой данных
 */
class Render
{

    /**
     * Отрисовка списка новостей для админки
     * @param $news - массив новостей
     * @return string - html строка
     */
    public static function adminAsideNewsList($news)
    {
        if(!is_array($news)){
            return $html = '<div>Произошла ошибка</div>';
        }
        $html = '';

        foreach ($news as $item){
            $html .= '<a href="/admin/news/' . $item['id'] . '">' . $item['title'] . '</a>';
        }

        return $html;
    }

    /**
     * Отрисовка списка новостей
     * @param $news - массив новостей
     * @return string - html строка
     */
    public static function renderCaregories($cats){

        if(!is_array($cats)){
            return $html = '<option data-id="0">Без категории</option>';
        }

        $html = '<option data-id="0">Без категории</option>';

        foreach ($cats as $item){
            $html .= '<option data-id="' . $item['id'] . '">' . $item['title'] . '</option>';
        }

        return $html;
    }



    /**
     * Рендеринг новостей на клиентской части сайта
     * @param $news
     * @return string
     */
    public static function renderFrontendNewsList($news){
        if(!is_array($news)){
            return $html = '<div>Произошла ошибка</div>';
        }
        $html = '';
        foreach ($news as $items => $item){
            $html .= '<div class="news-block">';
            $html .= '<h3 class="news-block__title">' . $item['title'] . '</h3>';
            $html .= '<div class="news-block__description">' . $item['content'] . '</div>';
            $html .= '<div class="news-block__footer">';
            $html .= '<div class="news-block__category">' . $item['category'] . '</div>';
            $html .= '<a class="news-block__button" href="' . $item['id'] . '">Читать далее</a>';
            $html .= '</div>';
            $html .= '</div>';
        }
        return $html;
    }
}