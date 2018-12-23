<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 14.07.2018 12:58
 */

namespace Angryjack\controllers;

use Angryjack\models\Article;

/**
 * Class articlesController Контроллер статей
 * @package Angryjack\controllers
 */
class ArticleController extends Controller
{
    protected $data;
    protected $instance;

    /**
     * ArticleController constructor.
     */
    public function __construct()
    {
        // получаем данные
        $this->data = parent::getData();
        // создаем объект статьи
        $this->instance = new Article();
    }

    /**
     * Показать все
     * @return array
     */
    public function actionShowAll() : array
    {
        $data = $this->data;

        if (empty($data->page)) {
            $page = 1;
        } else {
            $page = intval($data->page);
        }

        return array(
            $this->instance->showAll($page)
        );
    }

    /**
     * Показать конкретную
     * @return array
     */
    public function actionShow() : array
    {
        $data = $this->data;

        return array(
            $this->instance->show($data->id)
        );
    }

    /**
     * Создать статью
     * @return bool
     */
    public function actionCreate() : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->create($data);
    }

    /**
     * Редактировать статью
     * @return bool
     */
    public function actionEdit() : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->edit($data->id, $data);
    }

    /**
     * Удалить статью
     * @return bool
     */
    public function actionDelete() : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->delete($data->id);
    }

    /**
     * Поиск по статьям
     * @return array
     */
    public function actionSearch() : array
    {
        $data = $this->data;

        $articles = $this->instance->search($data->search);

        return array(
            $articles
        );
    }
}
