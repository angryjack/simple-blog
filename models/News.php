<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:53
 */


/**
 * Модель по работе с новостями на сайте
 */
class News
{
    /**
     * Получить список новостей (сначала новые)
     * @param $page - страница новостей
     * @return  array|bool - список новостей или false если их нет
     */
    public static function getNewsList($page = 1)
    {
        $page = intval($page);
        $limit = 2;
        $offset = ($page - 1) * $limit;

        $db = Db::getConnection();
        $sql = 'SELECT news.id AS "id",
                       news.title AS "title",
                       news.content AS "content",
                       categories.title AS "category"
                FROM news LEFT JOIN categories
                ON news.category = categories.id
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset';
        $result = $db->prepare($sql);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();
        $newsList = $result->fetchAll();
        $db = $result = null;

        if ($newsList) {
            return $newsList;
        }

        return false;
    }

    /**
     * Получаем конкретную новость
     * @param $id - ID новости
     * @return bool|mixed - объект новости, либо false если ее не существует
     */
    public static function getNewsByID($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM news WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_OBJ);
        $result->execute();
        $news = $result->fetch();
        $db = $sql = $result = null;

        if ($news) {
            return $news;
        }

        return false;
    }

    /**
     * Метод создания новости
     * @param $token
     * @param $title
     * @param $content
     * @param $category
     * @param null $url
     * @param null $description
     * @param null $keywords
     * @return bool
     */
    public static function createNews($token, $title, $content, $category, $url = null, $description = null, $keywords = null)
    {
        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (strlen($title) < 2) {
            die(NEWS_CREATE_ERROR_TITLE_LENGTH);
        }
        if (strlen($content) < 2) {
            die(NEWS_CREATE_ERROR_CONTENT_LENGTH);
        }
        //todo сделать проверку чпу ссылки перед добавлением

        $db = Db::getConnection();
        $sql = 'INSERT INTO news (title, content, category, description, keywords) VALUES (:title, :content, :category, :description, :keywords)';
        $result = $db->prepare($sql);
        $result->bindParam(':title', $title, PDO::PARAM_STR);
        $result->bindParam(':content', $content, PDO::PARAM_STR);
        $result->bindParam(':category', $category, PDO::PARAM_STR);
        $result->bindParam(':description', $description, PDO::PARAM_STR);
        $result->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        if ($result->execute()) {
            if($url){
                $lastId = $db->lastInsertId();
                $internal_route = "news/getNewsByID/$lastId";
                $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
                $result = $db->prepare($sql);
                $result->bindParam(':url', $url, PDO::PARAM_STR);
                $result->bindParam(':internal_route', $internal_route, PDO::PARAM_STR);
                $result->execute();
            }

            return true;
        }

        return false;
    }

    /**
     * Метод редактирования новости
     * @param $token
     * @param $id
     * @param $title
     * @param $content
     * @param $category
     * @return bool
     */
    public static function editNews($token, $id, $title, $content, $category)
    {
        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (strlen($title) < 2) {
            die(NEWS_CREATE_ERROR_TITLE_LENGTH);
        }
        if (strlen($content) < 2) {
            die(NEWS_CREATE_ERROR_CONTENT_LENGTH);
        }
        if (!$id) {
            die(NEWS_NO_ID_SELECTED);
        }

        if (!self::getNewsByID($id)) {
            return false;
        }

        $db = Db::getConnection();
        $sql = 'UPDATE news SET title = :title, content = :content, category = :category WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':title', $title, PDO::PARAM_STR);
        $result->bindParam(':content', $content, PDO::PARAM_STR);
        $result->bindParam(':category', $category, PDO::PARAM_INT);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_OBJ);
        if ($result->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Метод удаления новости
     * @param $token
     * @param $id
     * @return bool
     */
    public static function deleteNews($token, $id)
    {
        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (!$id) {
            die(NEWS_NO_ID_SELECTED);
        }

        if (!self::getNewsByID($id)) {
            return false;
        }

        $db = Db::getConnection();
        $sql = 'DELETE FROM news WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        if ($result->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Метод подсчета кол-ва новостей
     * @return mixed общее кол-во новостей
     */
    public static function countNews()
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT COUNT(*) FROM news');
        $quantity = $result->fetch();
        return $quantity[0];
    }

    /**
     * Получаем список категорий
     * @return array|bool
     */
    public static function getCatsList()
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT * FROM categories ORDER BY id DESC');
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $catsList = $result->fetchAll();
        $db = $result = null;
        if ($catsList) {
            return $catsList;
        }

        return false;
    }

    /**
     * Получаем конкретную категорию
     * @return object|bool
     */
    public static function getCategoryByID($id)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM categories WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_OBJ);
        $result->execute();
        $category = $result->fetch();
        $db = $sql = $result = null;

        if ($category) {
            return $category;
        }

        return false;
    }

    /**
     * Создаем новую категорию новостей
     * @param $token
     * @param $title
     * @return bool
     */
    public static function createCategory($token, $title)
    {
        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (strlen($title) < 2) {
            die(CATEGORY_CREATE_ERROR_TITLE_LENGTH);
        }

        $db = Db::getConnection();
        $sql = 'INSERT INTO categories (title) VALUES (:title)';
        $result = $db->prepare($sql);
        $result->bindParam(':title', $title, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_OBJ);
        if ($result->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Редактируем категорию
     * @param $token
     * @param $id
     * @param $title
     * @return bool
     */
    public static function editCategory($token, $id, $title)
    {
        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }

        if (strlen($title) < 2) {
            die(CATEGORY_CREATE_ERROR_TITLE_LENGTH);
        }

        if (!$id) {
            die(CATEGORY_NO_ID_SELECTED);
        }

        if (!self::getCategoryByID($id)) {
            return false;
        }

        $db = Db::getConnection();
        $sql = 'UPDATE categories SET title = :title WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':title', $title, PDO::PARAM_STR);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->setFetchMode(PDO::FETCH_OBJ);
        if ($result->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Метод удаления категории
     * @param $token
     * @param $id
     * @return bool
     */
    public static function deleteCategory($token, $id)
    {
        if (!Admin::checkAccess($token)) {
            die(ACCESS_DENIED);
        }
        if (!$id) {
            die(CATEGORY_NO_ID_SELECTED);
        }

        if (!self::getCategoryByID($id)) {
            return false;
        }

        $db = Db::getConnection();
        $sql = 'DELETE FROM categories WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        if ($result->execute()) {
            return true;
        }

        return false;
    }

}
