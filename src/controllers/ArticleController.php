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
        $this->data = $this->getData();
        $this->instance = new Article();
    }

    /**
     * Показать все
     * @throws \Exception
     */
    public function index() : object
    {
        $data = $this->data;

        if (empty($data->page)) {
            $page = 1;
        } else {
            $page = intval($data->page);
        }

        $data = $this->instance->showAll($page);

        return $this->view('site.articles', $data);
    }

    /**
     * @return array|null
     * @throws \Exception
     */
    public function listing() : ?array
    {
        $page = 1;
        return $this->instance->showAll($page);
    }

    /**
     * Показать конкретную
     * @param $id
     * @return object
     * @throws \Exception
     */
    public function show($id) : object
    {
        $data = array(
            $this->instance->show($id)
        );

        return $this->view('site.article', $data);
    }

    /**
     * Создать статью
     * @return bool
     * @throws \Exception
     */
    public function store() : bool
    {
        $data = $this->data;

        $this->checkAccess($data->token);

        return $this->instance->store($data);
    }

    /**
     * Редактировать статью
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function update($id) : bool
    {
        $data = $this->data;

        $this->checkAccess($data->token);

        return $this->instance->update($id, $data);
    }

    /**
     * Удалить статью
     * @return bool
     * @throws \Exception
     */
    public function destroy() : bool
    {
        $data = $this->data;

        $this->checkAccess($data->token);

        return $this->instance->destroy($data->id);
    }

    /**
     * Поиск по статьям
     * @return array
     * @throws \Exception
     */
    public function search() : array
    {
        $data = $this->data;

        $articles = $this->instance->search($data->search);

        return array(
            $articles
        );
    }
}
