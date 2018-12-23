<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.07.2018 20:32
 */

namespace Angryjack\models;

use Angryjack\exceptions\BaseException;
use Angryjack\helpers\Link;
use PDO;

class Category extends Model
{
    public $category;

    public function __construct($category = false)
    {
        $this->category = $category;
    }

    /**
     * Получаем категории
     * @param int $page
     * @return array
     * @throws BaseException
     */
    public function getCategories($page = 1)
    {
        $page = intval($page);

        if (! $page) {
            throw new BaseException('Не указана страница.');
        }

        $limit = 40;
        $offset = ($page - 1) * $limit;

        $db = Db::getConnection();
        $sql = 'SELECT categories.id AS "id",
                       categories.title AS "title",
                       categories.content AS "content",                  
                       routes.url AS "url"
                FROM categories 
                LEFT JOIN routes ON categories.link_id = routes.id
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $categories = $stmt->fetchAll();

        if (! $categories) {
            throw new BaseException('Категории не найдены.');

        }

        return $categories;
    }

    /**
     * Получаем категорию по ID
     * @param $id
     * @return mixed
     * @throws BaseException
     */
    public function getCategory($id)
    {
        if (! $id) {
            throw new BaseException('Не указан id категории.');
        }

        $db = Db::getConnection();
        $sql = 'SELECT categories.id AS "id",
                       categories.title AS "title",
                       categories.content AS "content",
                       categories.description AS "description",
                       categories.keywords AS "keywords",
                       routes.url AS "url"
                FROM categories 
                LEFT JOIN routes ON categories.link_id = routes.id
                WHERE categories.id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $category = $stmt->fetch();

        if (! $category) {
            throw new BaseException("Категория с ID = $id не найдена.");
        }

        return $category;
    }

    /**
     * Создание категории
     * @param $token
     * @param $title
     * @param null $content
     * @param null $description
     * @param null $keywords
     * @param null $url
     * @return bool
     * @throws BaseException
     */
    public function createCategory($token)
    {

        if (! parent::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        $this->validate();

        // если ЧПУ передан, проверяем, существует ли он
        if ($this->category->url) {
            if (parent::checkExistence($this->category->url)) {
                throw new BaseException('Данная короткая ссылка уже используется.');
            }
        }

        $db = Db::getConnection();
        $sql = 'INSERT INTO categories (title, content, description, keywords) VALUES (:title, :content, :description, :keywords)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $this->category->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->category->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->category->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $this->category->keywords, PDO::PARAM_STR);
        if ($stmt->execute()) {
            //если есть ссылка - то добавляем ее
            if ($this->category->url) {
                $categoryId = $db->lastInsertId();
                $internal_route = "site/category/$categoryId";
                $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $this->category->url, PDO::PARAM_STR);
                $stmt->bindParam(':internal_route', $internal_route, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    // связываем ид ссылки и категорию
                    $linkID = $db->lastInsertId();
                    $sql = 'UPDATE categories SET link_id = :link_id WHERE id = :id';
                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':link_id', $linkID, PDO::PARAM_INT);
                    $stmt->bindParam(':id', $categoryId, PDO::PARAM_INT);
                    if (!$stmt->execute()) {
                        throw new \Exception('Ошибка связывания ссылки с категорией.');
                    }
                }
            }
            return true;
        }
        throw new BaseException('Произошла ошибка при создании категории.');
    }

    /**
     * Редактирование категории
     * @param $token
     * @param $id
     * @param $title
     * @param null $content
     * @param null $description
     * @param null $keywords
     * @param null $url
     * @return bool
     * @throws BaseException
     */
    public function editCategory($token, $id)
    {
        if (! parent::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        if (! $id) {
            throw new BaseException('Не указан ID статьи.');
        }

        // проверяем существование категории перед ее редактированием
        if (! $this->checkCategoryExist($id)) {
            throw new BaseException("Категории с ID = $id не существует.");
        }

        $this->validate();

        // если ЧПУ передан, проверяем, существует ли он
        if ($this->category->url) {
            // если ссылка уже существует, но закреплена за другим элементом
            $category = self::getCategory($id);
            if ($this->category->url && $category->url != $this->category->url) {
                throw new BaseException('Данная ссылка уже занята.');
            }
        }

        $db = Db::getConnection();
        $sql = 'UPDATE categories 
                SET title = :title, content = :content, description = :description, keywords = :keywords
                WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $this->category->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->category->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $this->category->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $this->category->keywords, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            //проверяем закреплена ли ЧПУ за категорией
            $db = Db::getConnection();
            $sql = 'SELECT link_id FROM categories WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute();
            $linkCheckExist = $stmt->fetch();

            //если ссылка закреплена и новая ссылка передана выполняем UPDATE
            if ($linkCheckExist->link_id != null && $this->category->url) {
                $db = Db::getConnection();
                $sql = 'UPDATE routes SET url = :url WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $this->category->url, PDO::PARAM_STR);
                $stmt->bindParam(':id', $linkCheckExist->link_id, PDO::PARAM_INT);
                $stmt->execute();

                // если ссылка не существует, но новая ссылка была передана, выполняем INSERT и связываем
                // с таблицей категорий
            } else if ($linkCheckExist->link_id == null && $this->category->url) {
                $db = Db::getConnection();
                $internal_route = "site/category/$id";
                $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $this->category->url, PDO::PARAM_STR);
                $stmt->bindParam(':internal_route', $internal_route, PDO::PARAM_STR);
                $stmt->execute();
                $linkID = $db->lastInsertId();

                $sql = 'UPDATE categories SET link_id = :link_id WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':link_id', $linkID, PDO::PARAM_INT);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // если ссылка существует, но новая ссылка не была передана, выполняем DELETE
            } else if ($linkCheckExist->link_id != null && !$this->category->url) {
                // получить id существующей ссылки
                // удалить существующую ссылку
                // очистить поле link_id
                $db = Db::getConnection();
                $sql = 'DELETE FROM routes WHERE id = (SELECT link_id FROM articles WHERE id = :id)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $sql = 'UPDATE categories SET link_id = NULL WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }

            return true;
        }
        throw new BaseException('Произошла ошибка при редактировании категории.');
    }

    /**
     * Удаление категории
     * @param $token
     * @param $id
     * @return bool
     * @throws BaseException
     */
    public function deleteCategory($token, $id)
    {
        if (! parent::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        if (! $id) {
            throw new BaseException('Не указан ID категории.');
        }

        // проверяем существование статьи перед ее редактированием
        if (! $this->checkCategoryExist($id)) {
            throw new BaseException("Категории с ID = $id не существует.");
        }

        // если у статьи есть ЧПУ
        $db = Db::getConnection();
        $sql = 'SELECT link_id FROM categories WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $linkID = $stmt->fetch();

        if ($linkID->link_id) {
            $sql = 'DELETE FROM routes WHERE id = :link_id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':link_id', $linkID->link_id, PDO::PARAM_INT);
            $stmt->execute();
        }

        //удаляем ссылки на данную категорию из всех статей
        $db = Db::getConnection();
        $sql = 'UPDATE articles SET category = 0 WHERE category = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        //удаляем саму категорию
        $db = Db::getConnection();
        $sql = 'DELETE FROM categories WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return true;
        }

        throw new BaseException('Произошла ошибка при удалении категории.');
    }

    /**
     * Поиск по категориям
     * @param $search
     * @return array
     * @throws BaseException
     */
    public function searchCategories($search)
    {

        if (strlen($search) < 2) {
            throw new BaseException('Строка поиска слишком короткая.');
        }

        $db = Db::getConnection();
        $sql = 'SELECT categories.id AS "id", 
                           categories.title AS "title",
                           categories.description AS "description",
                           routes.url AS "url"
                    FROM categories
                    LEFT JOIN routes ON categories.link_id = routes.id
                    WHERE categories.title LIKE :search
                    ORDER BY title ASC';
        $stmt = $db->prepare($sql);
        $search = "%$search%";
        $stmt->bindParam(':search', $search, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $categories = $stmt->fetchAll();

        if ($categories) {
            return $categories;
        }

        throw new BaseException('Подходящие категории не найдены.');
    }

    /**
     * Проверяем существование категории
     * @param $id - ID категории
     * @return bool - true если категория существует, false - не существует
     * @throws BaseException
     */
    public function checkCategoryExist($id)
    {
        if (! $id) {
            throw new BaseException('Не указан id категории.');
        }

        $db = Db::getConnection();
        $sql = 'SELECT id, title FROM categories WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $category = $stmt->fetch();

        if ($category) {
            return true;
        }

        return false;
    }

    /**
     * Считаем общее кол-во статей
     * @return int
     * @throws \Exception
     */
    public function countArticles()
    {
        $db = Db::getConnection();
        $result = $db->query('SELECT COUNT(*) FROM categories');
        $quantity = $result->fetch();

        if ($quantity) {
            return (int)$quantity[0];
        }

        return 0;
    }

    public function validate()
    {
        $category = $this->category;

        if (strlen($category->title) < 2) {
            throw new BaseException('Слишком короткий заголовок.');
        }
    }
}
