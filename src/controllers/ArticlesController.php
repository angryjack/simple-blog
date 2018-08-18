<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 14.07.2018 12:58
 */

namespace Angryjack\controllers;
use Angryjack\exceptions\BaseException;
use Angryjack\models\Articles;
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

            $articles = Articles::getArticles($page);
            $result['status'] = 'success';
            $result['answer']['data'] = $articles;
        }
        catch (BaseException $e) {
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
            $article = Articles::getArticle($data->id);
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

            if ($data->title && $data->content) {

                if (!isset($data->category) || strlen(trim($data->category)) == 0) {
                    $data->category = 0;
                }
                if (!isset($data->url)) {
                    $data->url = false;
                }
                if (!isset($data->description)) {
                    $data->description = false;
                }
                if (!isset($data->keywords)) {
                    $data->keywords = false;
                }
                if (isset($data->url)) {
                    $data->url = trim($data->url);
                }

                if (Articles::createArticle($data->token, $data->title, $data->content, $data->category, $data->url, $data->description, $data->keywords)) {
                    $result['status'] = 'success';
                    $result['answer']['text'] = 'Новость упешно создана.';
                    $result['answer']['code'] = 'ARTICLE_CREATE_SUCCESS';
                }

            } else {
                $result['status'] = 'error';
                $result['answer']['text'] = 'Заполните все обязательные поля.';
                $result['answer']['code'] = 'ARTICLE_EMPTY_FIELDS';
            }
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
            $data = Site::getData();

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            if ($data->id && $data->title && $data->content) {

                if (!isset($data->category) || strlen(trim($data->category)) == 0) {
                    $data->category = 0;
                }
                if (!isset($data->url)) {
                    $data->url = false;
                }
                if (!isset($data->description)) {
                    $data->description = false;
                }
                if (!isset($data->keywords)) {
                    $data->keywords = false;
                }
                if (isset($data->url)) {
                    $data->url = trim($data->url);
                }

                $editArticleResult = Articles::editArticle($data->token, $data->id, $data->title, $data->content,
                    $data->category, $data->url, $data->description, $data->keywords);

                if ($editArticleResult) {
                    $result['status'] = 'success';
                    $result['answer']['text'] = 'Новость упешно отредактирована.';
                    $result['answer']['code'] = 'ARTICLE_EDIT_SUCCESS';

                } else {
                    throw new BaseException('Произошла ошибка при редактировании статьи.');
                }

            } else {
                throw new BaseException('Заполните все обязательные поля.');
            }
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
            $data = Site::getData();

            if (!Site::checkAccess($data->token)) {
                throw new BaseException('Доступ запрещен.');
            }

            if ($data->id) {

                Articles::deleteArticle($data->token, $data->id);
                $result['status'] = 'success';
                $result['answer']['text'] = 'Новость упешно удалена.';
                $result['answer']['code'] = 'ARTICLE_DELETE_SUCCESS';

            } else {
                throw new BaseException('Не указан ID статьи.');
            }

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
            $data = Site::getData();

            if ($data->search) {
                $articles = Articles::searchArticles($data->search);

                $result['status'] = 'success';
                $result['answer']['data'] = $articles;

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