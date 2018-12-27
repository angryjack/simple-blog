<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.07.2018 14:24
 */

namespace Angryjack\controllers;

use Angryjack\models\Category;

/**
 * Class categoriesController Котроллер категорий
 * @package Angryjack\controllers
 */
class CategoryController extends Controller
{
    protected $data;
    protected $instance;

    public function __construct()
    {
        // получаем данные
        $this->data = $this->getData();
        // создаем объект
        $this->instance = new Category();
    }

    /**
     * Показать все
     * @return array
     * @throws \Exception
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
            $this->instance->getAll($page)
        );
    }

    /**
     * Показать конкретную новость
     * @return array
     * @throws \Exception
     */
    public function actionShow() : array
    {
        $data = $this->data;

        return array(
            $this->instance->get($data->id)
        );
    }

    /**
     * Создать
     * @return bool
     * @throws \Angryjack\exceptions\BaseException
     */
    public function actionCreate() : bool
    {
        $data = $this->data;

        $this->checkAccess($data->token);

        return $this->instance->create($data);
    }

    /**
     * Редактировать
     * @return bool
     * @throws \Angryjack\exceptions\BaseException
     */
    public function actionEdit() : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->edit($data->id, $data);
    }

    /**
     * Удалить
     * @return bool
     * @throws \Exception
     */
    public function actionDelete() : bool
    {
        $data = $this->data;

        parent::checkAccess($data->token);

        return $this->instance->delete($data->id);
    }

    /**
     * Поиск по категориям
     * @return array
     * @throws \Exception
     */
    public function actionSearch() : array
    {
        $data = $this->data;

        return array(
            $this->instance->search($data->search)
        );
    }
}
