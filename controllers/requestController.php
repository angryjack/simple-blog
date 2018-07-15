<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 11.07.2018 22:52
 */

/**
 * Class requestController класс для обработки запросов
 * Обязательно должен возвращать boolean после вызова метода
 */
class requestController
{
    /**
     * Вход в панель администратора
     * @return bool
     */
    public function actionAdminLogin()
    {
        if (isset($_POST['login']) && isset($_POST['passwd']) &&
            !empty($_POST['login']) && !empty($_POST['passwd'])
        ) {
            $login = $_POST['login'];
            $passwd = $_POST['passwd'];

            // проверяем есть ли пользователь и правильность логина и пароля
            $token = Admin::login($login, $passwd);

            if ($token) {
                $result['status'] = 'success';
                $result['token'] = $token;

            } else {
                $result['status'] = 'error';
                $result['error_text'] = AUTH_ERROR;
            }

        } else {
            $result['status'] = 'error';
            $result['error_text'] = EMPTY_LOGIN_FIELDS;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Получаем спосок новостей (клиентская часть)
     * POST param - page ID
     * @return bool
     */
    public function actionGetNewsList()
    {
        if (isset($_POST['page'])) {
            $page = intval($_POST['page']);
        } else {
            $page = 1;
        }
        $news = News::getNewsList($page);

        if ($news) {
            $result['status'] = 'success';
            $result['news'] = Render::renderFrontendNewsList($news);

        } else {
            $result['status'] = 'error';
            $result['error_text'] = NO_NEWS;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Получаем таблицу новостей для админки
     */
    public function actionGetAsideNewsList()
    {
        if (isset($_POST['page'])) {
            $page = intval($_POST['page']);
        } else {
            $page = 1;
        }
        $news = News::getNewsList($page);

        if ($news) {
            $result['status'] = 'success';
            $result['news'] = Render::adminAsideNewsList($news);

        } else {
            $result['status'] = 'error';
            $result['error_text'] = NO_NEWS;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Метод добавления новости
     * получаем параметры через POST
     * @return bool
     */
    public function actionAddNews()
    {
        $token = Security::getToken();

        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (isset($_POST['title']) && isset($_POST['content'])) {
            $title = $_POST['title'];
            $content = $_POST['content'];

            if (!isset($_POST['category'])) {
                $category = 0;
            } else {
                $category = intval($_POST['category']);
            }

            if (!isset($_POST['url'])) {
                $url = null;
            } else {
                $url = $_POST['url'];
            }

            if (!isset($_POST['description'])) {
                $description = null;
            } else {
                $description = $_POST['description'];
            }

            if (!isset($_POST['keywords'])) {
                $keywords = null;
            } else {
                $keywords = $_POST['keywords'];
            }

            if (News::createNews($token, $title, $content, $category, $url, $description, $keywords)) {
                $result['status'] = 'success';
                $result['text'] = NEWS_CREATE_SUCCESS;

            } else {
                $result['status'] = 'error';
                $result['error_text'] = NEWS_CREATE_ERROR;
            }

        } else {
            $result['status'] = 'error';
            $result['error_text'] = NEWS_EMPTY_FIELDS;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Метод редактирования новости
     * @return bool
     */
    public function actionEditNews()
    {
        $token = Security::getToken();

        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (isset($_POST['id']) && isset($_POST['title']) && isset($_POST['content'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $content = $_POST['content'];

            if (!isset($_POST['category'])) {
                $category = 0;
            } else {
                $category = $_POST['category'];
            }

            if (News::editNews($token, $id, $title, $content, $category)) {
                $result['status'] = 'success';
                $result['text'] = NEWS_EDIT_SUCCESS;

            } else {
                $result['status'] = 'error';
                $result['error_text'] = NEWS_EDIT_ERROR;
            }

        } else {
            $result['status'] = 'error';
            $result['error_text'] = NEWS_EMPTY_FIELDS;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Метод удаления новости
     * @return bool
     */
    public function actionDeleteNews()
    {
        $token = Security::getToken();

        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            if (News::deleteNews($token, $id)) {
                $result['status'] = 'success';
                $result['text'] = NEWS_DELETE_SUCCESS;

            } else {
                $result['status'] = 'error';
                $result['error_text'] = NEWS_DELETE_ERROR;
            }

        } else {
            $result['status'] = 'error';
            $result['error_text'] = NEWS_EMPTY_FIELDS;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Получаем список категорий
     * @return bool
     */
    public function actionGetCategories()
    {
        $cats = News::getCatsList();

        if ($cats) {
            $result['status'] = 'success';
            $result['categories'] = Render::renderCaregories($cats);

        } else {
            $result['status'] = 'error';
            $result['error_text'] = NO_CATEGORIES;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Метод добавления категории
     * @return bool
     */
    public function actionAddCategory()
    {
        $token = Security::getToken();

        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (isset($_POST['title'])) {
            $title = $_POST['title'];

            if (News::createCategory($token, $title)) {
                $result['status'] = 'success';
                $result['text'] = CATEGORY_CREATE_SUCCESS;

            } else {
                $result['status'] = 'error';
                $result['text'] = CATEGORY_CREATE_ERROR;
            }

        } else {
            $result['status'] = 'error';
            $result['text'] = CATEGORY_CREATE_ERROR;
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

        $token = Security::getToken();

        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (isset($_POST['id']) && isset($_POST['title'])) {
            $id = $_POST['id'];
            $title = $_POST['title'];
            if (News::editCategory($token, $id, $title)) {
                $result['status'] = 'success';
                $result['text'] = CATEGORY_EDIT_SUCCESS;

            } else {
                $result['status'] = 'error';
                $result['error_text'] = CATEGORY_EDIT_ERROR;
            }

        } else {
            $result['status'] = 'error';
            $result['error_text'] = CATEGORY_EDIT_ERROR;
        }

        echo json_encode($result);
        return true;
    }

    /**
     * Метод удаления категории
     * @return bool
     */
    public function actionDeleteCategory()
    {

        $token = Security::getToken();

        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (isset($_POST['id'])) {
            $id = $_POST['id'];

            if (News::deleteCategory($token, $id)) {
                $result['status'] = 'success';
                $result['text'] = CATEGORY_DELETE_SUCCESS;

            } else {
                $result['status'] = 'error';
                $result['error_text'] = CATEGORY_DELETE_ERROR;
            }

        } else {
            $result['status'] = 'error';
            $result['error_text'] = CATEGORY_DELETE_ERROR;
        }

        echo json_encode($result);
        return true;
    }

}