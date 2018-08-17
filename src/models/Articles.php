<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:53
 */

namespace Angryjack\models;
use Angryjack\exceptions\BaseException;
use PDO;

/**
 * Модель по работе с новостями на сайте
 */
class Articles
{
    /**
     * Получаем статьи
     * @param int $page страница
     * @return array возвращает массив со статьями
     * @throws BaseException - ошибки
     */
    public static function getArticles($page = 1)
    {
        $page = intval($page);

        if(!$page){
            throw new BaseException('Не указана страница.');
        }

        $limit = 20;
        $offset = ($page - 1) * $limit;

        $db = Db::getConnection();
        $sql = 'SELECT articles.id AS "id",
                       articles.title AS "title",
                       articles.content AS "content",
                       categories.title AS "category",
                       routes.url AS "url"
                FROM articles
                LEFT JOIN categories ON articles.category = categories.id
                LEFT JOIN routes ON articles.link_id = routes.id
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $articles = $stmt->fetchAll();

        if(!$articles){
            throw new BaseException('Статей нет.');
        }

        return $articles;
    }

    /**
     * @param $id - ид статьи
     * @return mixed - массив с данными о статье
     * @throws BaseException - ошибки
     */
    public static function getArticle($id)
    {
        if(!$id){
            throw new BaseException('Не указан id статьи.');
        }

        $db = Db::getConnection();
        $sql = 'SELECT articles.id AS "id",
                       articles.title AS "title",
                       articles.content AS "content",
                       routes.url AS "url",
                       articles.description AS "description",
                       articles.keywords AS "keywords",
                       categories.id AS "category_id",
                       categories.title AS "category"
                FROM articles 
                LEFT JOIN categories ON articles.category = categories.id
                LEFT JOIN routes ON articles.link_id = routes.id
                WHERE articles.id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $article = $stmt->fetch();

        if(!$article){
            throw new BaseException("Статья с $id ID не найдена.");
        }

        return $article;
    }

    /**
     * Создаем новость
     * @param $token
     * @param $title
     * @param $content
     * @param int $category
     * @param null $url
     * @param null $description
     * @param null $keywords
     * @return bool
     * @throws BaseException
     */
    public static function createArticle($token, $title, $content, $category = 0, $url = null, $description = null, $keywords = null)
    {

        if (!Site::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        if (strlen($title) < 2) {
            throw new BaseException('Слишком короткий заголовок.');
        }

        if (strlen($content) < 2) {
           throw new BaseException('Слишком короткая длина контента.');
        }

        // если ЧПУ передан, проверяем, существует ли он
        if ($url) {
            $db = Db::getConnection();
            $sql = 'SELECT url FROM routes WHERE url = :url';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':url', $url, PDO::PARAM_STR);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            $check = $stmt->fetch();

            if ($check) {
                throw new BaseException('Данная короткая ссылка уже используется.');
            }
        }

        $db = Db::getConnection();
        $sql = 'INSERT INTO articles (title, content, category, description, keywords) VALUES (:title, :content, :category, :description, :keywords)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        if ($stmt->execute()) {
            //если есть ссылка - то добавляем ее
            if ($url) {
                $articleId = $db->lastInsertId();
                $internal_route = "site/article/$articleId";
                $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $url, PDO::PARAM_STR);
                $stmt->bindParam(':internal_route', $internal_route, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    // связываем ид ссылки и новости
                    $linkID = $db->lastInsertId();
                    $sql = 'UPDATE articles SET link_id = :link_id WHERE id = :id';
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':link_id', $linkID, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
                    if (!$stmt->execute()) {
                        throw new \Exception('Ошибка связывания ссылки со статьей.');
                    }
                }
            }
            return true;

        }

        throw new BaseException('Произошла ошибка при создании статьи.');

    }

    /**
     * Метод редактирования новости
     * @param $token
     * @param $id
     * @param $title
     * @param $content
     * @param $category
     * @param null $url
     * @param null $description
     * @param null $keywords
     * @return mixed
     * @throws BaseException
     */
    public static function editArticle($token, $id, $title, $content, $category, $url = null, $description = null, $keywords = null)
    {
        if (!Site::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        if (!$id) {
            throw new BaseException('Не указан ID статьи.');
        }

        if (strlen($title) < 2) {
            throw new BaseException('Слишком короткий заголовок.');
        }

        if (strlen($content) < 2) {
            throw new BaseException('Слишком короткая длина контента.');
        }

        // проверяем существование статьи перед ее редактированием
        if (!self::checkArticleExist($id)) {
            throw new BaseException("Статьи с ID = $id не существует.");
        }

        // если ЧПУ передан, проверяем, существует ли он
        if ($url) {
            $db = Db::getConnection();
            $sql = 'SELECT url FROM routes WHERE url = :url';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':url', $url, PDO::PARAM_STR);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            $check = $stmt->fetch();

            // если ссылка уже существует, но закреплена за другим элементом
            $article = self::getArticle($id);
            if ($check && $article->url != $url) {
                throw new BaseException('Данная ссылка уже занята.');
            }
        }

        $db = Db::getConnection();
        $sql = 'UPDATE articles 
                SET title = :title, content = :content, category = :category, description = :description, keywords = :keywords
                WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $content, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $keywords, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {

            //проверяем закреплена ли ЧПУ за статьей
            $db = Db::getConnection();
            $sql = 'SELECT link_id FROM articles WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            $linkCheckExist = $stmt->fetch();

            //если ссылка закреплена и новая ссылка передана выполняем UPDATE
            if ($linkCheckExist->link_id != null && $url) {

                $db = Db::getConnection();
                $sql = 'UPDATE routes SET url = :url WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $url, PDO::PARAM_STR);
                $stmt->bindParam(':id', $linkCheckExist->link_id, PDO::PARAM_INT);
                $stmt->execute();

                // если ссылка не существует, но новая ссылка была передана, выполняем INSERT в таблицу ссылок и связываем
                // с таблицей статей
            } else if ($linkCheckExist->link_id == null && $url) {

                $db = Db::getConnection();
                $internal_route = "site/article/$id";
                $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $url, PDO::PARAM_STR);
                $stmt->bindParam(':internal_route', $internal_route, PDO::PARAM_STR);
                $stmt->execute();
                $linkID = $db->lastInsertId();

                $sql = 'UPDATE articles SET link_id = :link_id WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':link_id', $linkID, PDO::PARAM_INT);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // если ссылка существует, но новая ссылка не была передана, выполняем DELETE
            } else if ($linkCheckExist->link_id != null && !$url) {

                // получить id существующей ссылки
                // удалить существующую ссылку
                // очистить поле link_id
                $db = Db::getConnection();
                $sql = 'DELETE FROM routes WHERE id = (SELECT link_id FROM articles WHERE id = :id)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $sql = 'UPDATE articles SET link_id = NULL WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }

            return true;

        }

        throw new BaseException('Произошла ошибка при редактировании статьи.');
    }

    /**
     * Удаление статьи
     * @param $token
     * @param $id
     * @return bool
     * @throws BaseException
     */
    public static function deleteArticle($token, $id)
    {
        if (!Site::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        if (!$id) {
            throw new BaseException('Не указан ID статьи.');
        }

        // проверяем существование статьи перед ее редактированием
        if (!self::checkArticleExist($id)) {
            throw new BaseException("Статьи с ID = $id не существует.");
        }

        //todo объеденить проверку существования статьи и получение ЧПУ
        // если у статьи есть ЧПУ
        $db = Db::getConnection();
        $sql = 'SELECT link_id FROM articles WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $linkID =  $stmt->fetch();

        if ($linkID->link_id) {
            $sql = 'DELETE FROM routes WHERE id = :link_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':link_id', $linkID->link_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        $db = Db::getConnection();
        $sql = 'DELETE FROM articles WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }

        throw new BaseException('Произошла ошибка при удалении статьи.');
    }

    /**
     * Поиск по статьям
     * @param $search
     * @return array
     * @throws BaseException
     */
    public static function searchArticles($search){

        if (strlen($search) < 2 ) {
            throw new BaseException('Строка поиска слишком короткая.');
        }
        $db = Db::getConnection();
        $sql = 'SELECT articles.id AS "id",
                   articles.title AS "title",
                   articles.content AS "content",
                   categories.title AS "category",
                   routes.url AS "url"
            FROM articles
            LEFT JOIN categories ON articles.category = categories.id
            LEFT JOIN routes ON articles.link_id = routes.id
            WHERE articles.title LIKE :search
            ORDER BY title ASC';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $articles = $stmt->fetchAll();

        if ($articles) {
            return $articles;
        }

        throw new BaseException('Подходящие статьи не найдены.');
    }

    /**
     * Проверяем существование статьи
     * @param $id - ID статьи
     * @return bool - true если статья существует, false - статья не существует
     * @throws BaseException
     */
    public static function checkArticleExist($id){

        if(!$id){
            throw new BaseException('Не указан id статьи.');
        }

        $db = Db::getConnection();
        $sql = 'SELECT id, title FROM articles WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $article = $stmt->fetch();

        if($article){
            return true;
        }

        return false;
    }

    /**
     * Метод подсчета кол-ва статей
     * @return int общее кол-во статей
     */
    public static function countArticles()
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT COUNT(*) FROM articles');
        $quantity = $result->fetch();

        if ($quantity) {
            return (int)$quantity[0];
        }

        return 0;
    }

}
