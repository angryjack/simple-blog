<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.07.2018 14:24
 */

namespace Angryjack\controllers;

use Angryjack\exceptions\BaseException;
use Angryjack\models\Category;

/**
 * Class categoriesController Котроллер категорий
 * @package Angryjack\controllers
 */
class CategoryController extends Controller
{
    /**
     * Получаем список категорий (клиентская часть)
     * POST param - page ID
     * @return bool
     */
    public function actionGetCategories()
    {
        try {
            $data = parent::getData(false);
            if ($data->page) {
                $page = intval($data->page);
            } else {
                $page = 1;
            }

            $categoryManager = new Category();
            $categories = $categoryManager->getCategories($page);

            $result['status'] = 'success';
            $result['answer']['data'] = $categories;
        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Получаем конкретную категорию (клиентская часть)
     * POST param - page ID
     * @return bool
     */
    public function actionGetCategory()
    {
        try {
            $data = parent::getData();

            $categoryManager = new Category();
            $category = $categoryManager->getCategory($data->id);

            $result['status'] = 'success';
            $result['answer']['data'] = $category;
        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Метод добавления категории
     * получаем параметры через POST
     * @return bool
     */
    public function actionAddCategory()
    {
        try {
            $data = parent::getData(false);

            if (! parent::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            $categoryManager = new Category($data);
            $categoryManager->createCategory($data->token);

            $result['status'] = 'success';
            $result['answer']['text'] = 'Новость упешно создана.';
            $result['answer']['code'] = 'CATEGORY_CREATE_SUCCESS';

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Метод редактирования категории
     * @return bool
     */
    public function actionEditCategory()
    {
        try {
            $data = parent::getData();

            if (!parent::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            $categoryManager = new Category($data);
            $categoryManager->editCategory($data->token, $data->id);

            $result['status'] = 'success';
            $result['answer']['text'] = 'Категория упешно отредактирована.';
            $result['answer']['code'] = 'CATEGORY_EDIT_SUCCESS';
        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Удаления категории
     * @return bool
     */
    public function actionDeleteCategory()
    {
        try {
            $data = parent::getData();

            if (!parent::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            $categoryManager = new Category();
            $categoryManager->deleteCategory($data->token, $data->id);

            $result['status'] = 'success';
            $result['answer']['text'] = 'Категория упешно удалена.';
            $result['answer']['code'] = 'CATEGORY_DELETE_SUCCESS';
        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }

    /**
     * Поиск категории по заголовку
     */
    public static function actionSearchCategories()
    {
        try {
            $data = parent::getData();

            $categoryManager = new Category();
            $categories = $categoryManager->searchCategories($data->search);

            $result['status'] = 'success';
            $result['answer']['data'] = $categories;

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }
}
