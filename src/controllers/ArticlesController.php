<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 14.07.2018 12:58
 */

namespace Angryjack\controllers;

use Angryjack\exceptions\BaseException;
use Angryjack\models\Article;
use Angryjack\models\Site;

/**
 * Class articlesController Контроллер статей
 * @package Angryjack\controllers
 */
class ArticlesController
{
    /**
     * Получения всех статей
     * @return bool
     */
    public function actionGetArticles()
    {
        try {
            $data = Site::getData();
            if ($data->page) {
                $page = intval($data->page);
            } else {
                $page = 1;
            }
            $articleManager = new Article();
            $articles = $articleManager->getArticles(false, $page);

            $result['status'] = 'success';
            $result['answer']['data'] = $articles;
        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Получаем конкретную новость (клиентская часть)
     * POST param - page ID
     * @return bool
     */
    public function actionGetArticle()
    {
        try {
            $data = Site::getData();
            $articleManager = new Article($data);
            $article = $articleManager->getArticle($data->id);

            $result['status'] = 'success';
            $result['answer']['data'] = $article;

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Добавления новости
     * получаем параметры через POST
     * @return bool
     * @throws
     */
    public function actionAddArticle()
    {
        try {
            $data = Site::getData();

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            $articleManager = new Article($data);
            $articleManager->createArticle($data->token);

            $result['status'] = 'success';
            $result['answer']['text'] = 'Новость упешно создана.';
            $result['answer']['code'] = 'ARTICLE_CREATE_SUCCESS';

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Редактирование новости
     * @return bool
     * @throws BaseException
     */
    public function actionEditArticle()
    {
        try {
            $data = Site::getData(false);

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            if (!isset($data->id)) {
                throw new BaseException('Не указан id статьи.');
            }

            $articleManager = new Article($data);
            $articleManager->editArticle($data->token, $data->id);

            $result['status'] = 'success';
            $result['answer']['text'] = 'Новость упешно отредактирована.';
            $result['answer']['code'] = 'ARTICLE_EDIT_SUCCESS';

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Удаление статьи
     * @return bool
     * @throws BaseException
     */
    public function actionDeleteArticle()
    {
        try {
            $data = Site::getData(false);

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            if (!isset($data->id)) {
                throw new BaseException('Не указан id статьи.');
            }

            $articleManager = new Article();
            $articleManager->deleteArticle($data->token, $data->id);

            $result['status'] = 'success';
            $result['answer']['text'] = 'Новость упешно удалена.';
            $result['answer']['code'] = 'ARTICLE_DELETE_SUCCESS';

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Поиск статей по их заголовку
     * @return bool
     * @throws BaseException
     */
    public static function actionSearchArticles()
    {
        try {
            $data = Site::getData(false);

            if (!isset($data->search)) {
                throw new BaseException('Не заданы условия поиска.');
            }
            $articleManager = new Article();
            $articles = $articleManager->search($data->search);

            $result['status'] = 'success';
            $result['answer']['data'] = $articles;

        } catch (BaseException $e) {
            $result['status'] = 'error';
            $result['answer']['text'] = $e->getMessage();
        }

        echo json_encode($result);
        return true;
    }
}
