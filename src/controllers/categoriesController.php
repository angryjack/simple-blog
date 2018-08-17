<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.07.2018 14:24
 */

namespace Angryjack\controllers;
use Angryjack\exceptions\BaseException;
use Angryjack\models\Categories;
use Angryjack\models\Site;

/**
 * Class categoriesController Котроллер категорий
 * @package Angryjack\controllers
 */
class categoriesController
{
    /**
     * Получаем список категорий (клиентская часть)
     * POST param - page ID
     * @return bool
     */
    public function actionGetCategories()
    {
        try {
            $data = Site::getData();
            if ($data->page) {
                $page = intval($data->page);
            } else {
                $page = 1;
            }
            $categories = Categories::getCategories($page);
            $result['status'] = 'success';
            $result['answer']['data'] = $categories;
        }
        catch (BaseException $e) {
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
            $data = Site::getData();
            $category = Categories::getCategory($data->id);
            $result['status'] = 'success';
            $result['answer']['data'] = $category;
        }
        catch (BaseException $e){
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
            $data = Site::getData();

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            if ($data->title) {
                if(Categories::createCategory($data->token, $data->title, $data->description, $data->url)){
                    $result['status'] = 'success';
                    $result['answer']['text'] = 'Новость упешно создана.';
                    $result['answer']['code'] = 'CATEGORY_CREATE_SUCCESS';
                }
            } else {
                $result['status'] = 'error';
                $result['answer']['text'] = 'Заполните все обязательные поля.';
                $result['answer']['code'] = 'CATEGORY_EMPTY_FIELDS';
            }
        }
        catch (BaseException $e){
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
        try{
            $data = Site::getData();

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            if ($data->id && $data->title) {

                if(!isset($data->description)){
                    $data->description = false;
                }

                if(!isset($data->url)){
                    $data->url = false;
                }

                $editCategoryResult = Categories::editCategory($data->token, $data->id, $data->title, $data->description, $data->url);


                if ($editCategoryResult) {
                    $result['status'] = 'success';
                    $result['answer']['text'] = 'Категория упешно отредактирована.';
                    $result['answer']['code'] = 'CATEGORY_EDIT_SUCCESS';

                } else {
                    throw new BaseException('Произошла ошибка при редактировании категории.');
                }

            } else {
                throw new BaseException('Заполните все обязательные поля.');
            }
        }
        catch (BaseException $e){
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

            $data = Site::getData();

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            if ($data->id) {
                Categories::deleteCategory($data->token, $data->id);
                $result['status'] = 'success';
                $result['answer']['text'] = 'Категория упешно удалена.';
                $result['answer']['code'] = 'CATEGORY_DELETE_SUCCESS';

            } else {
                throw new BaseException('Не указан ID категории.');
            }
        }
        catch (BaseException $e){
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }
        echo json_encode($result);
        return true;
    }

    /**
     * Поиск категории по заголовку
     */
    public static function actionSearchCategories(){
        try {
            $data = Site::getData();

            if ($data->search) {
                $categories = Categories::searchCategories($data->search);

                $result['status'] = 'success';
                $result['answer']['data'] = $categories;

            } else {
                $result['status'] = 'error';
                $result['answer']['text'] = 'Не заданы условия поиска.';
            }

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }
}