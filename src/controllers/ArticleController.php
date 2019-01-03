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

        $data = $this->instance->getAll($page);

        return $this->view('site.articles', $data);
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
            $this->instance->get($id)
        );

        return $this->view('site.article', $data);
    }

    /**
     * Создать статью
     * @return bool
     * @throws \Angryjack\exceptions\BaseException
     */
    public function store() : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->create($data);
    }

    /**
     * Редактировать статью
     * @return bool
     * @throws \Angryjack\exceptions\BaseException
     */
    public function update($id) : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->edit($data->id, $data);
    }

    /**
     * Удалить статью
     * @return bool
     * @throws \Exception
     */
    public function destroy() : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->delete($data->id);
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
