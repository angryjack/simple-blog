<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:53
 */

namespace Angryjack\models;

use Angryjack\exceptions\BaseException;
use Angryjack\helpers\Link;
use PDO;

/**
 * Модель по работе с новостями на сайте
 */
class Article extends Model implements Action
{
    public $article;

    public function __construct()
    {
        //
    }

    public function showAll($page = 1): ?array
    {

        // TODO: Implement showAll() method.
    }

    public function show($id): ?array
    {
        // TODO: Implement show() method.
    }

    public function create(array $data): bool
    {
        // TODO: Implement create() method.
    }

    public function edit($id, array $data): bool
    {
        // TODO: Implement edit() method.
    }

    public function delete($id): bool
    {
        // TODO: Implement delete() method.
    }

    public function search(array $data): ?array
    {
        // TODO: Implement search() method.
    }

    /**
     * Получаем статьи
     * @param bool $category
     * @param int $page страница
     * @return array возвращает массив со статьями
     * @throws BaseException - ошибки
     */
    public function getArticles($category = false, $page = 1)
    {
        $page = intval($page);
        if ($category) {
            $category = intval($category);
        }
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $db = Db::getConnection();
        // если категория передана, то дописываем к нашему запросу условие WHERE
        $whereClause = '';
        if ($category) {
            $whereClause = 'WHERE articles.category = :category';
        }
        $sql = 'SELECT articles.id AS "id",
                       articles.title AS "title",
                       articles.content AS "content",                       
                       categories.title AS "category",
                       categories.id AS "category_id",                
                       (SELECT url FROM routes WHERE routes.id = categories.link_id) AS "category_link",
                       routes.url AS "url"
                FROM articles
                LEFT JOIN categories ON articles.category = categories.id
                LEFT JOIN routes ON articles.link_id = routes.id '
                . $whereClause .
                ' ORDER BY id DESC
                LIMIT :limit OFFSET :offset';
        $stmt = $db->prepare($sql);
        if ($category) {
            $stmt->bindParam(':category', $category, PDO::PARAM_INT);
        }
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $articles = $stmt->fetchAll();

        if (! $articles) {
            throw new BaseException('Статей нет.');
        }

        return $articles;
    }

    /**
     * @param $id - ид статьи
     * @return mixed - массив с данными о статье
     * @throws BaseException - ошибки
     */
    public function getArticle($id)
    {
        if (! $id) {
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

        if (! $article) {
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
    public function createArticle($token)
    {
        if (! parent::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        $this->validateArticle();

        // если ЧПУ передан, проверяем, существует ли он
        if ($this->article->url) {
            if (parent::checkExistence($this->article->url)) {
                throw new BaseException('Данная короткая ссылка уже используется.');
            }
        }

        $db = Db::getConnection();
        $sql = 'INSERT INTO articles (title, content, category, description, keywords, create_date) 
                VALUES (:title, :content, :category, :description, :keywords, UNIX_TIMESTAMP())';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $this->article->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->article->content, PDO::PARAM_STR);
        $stmt->bindParam(':category', $this->article->category, PDO::PARAM_INT);
        $stmt->bindParam(':description', $this->article->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $this->article->keywords, PDO::PARAM_STR);
        if ($stmt->execute()) {
            //если есть ссылка - то добавляем ее
            if ($this->article->url) {
                $articleId = $db->lastInsertId();
                $internal_route = "site/article/$articleId";
                $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $this->article->url, PDO::PARAM_STR);
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
    public function editArticle($token, $id)
    {
        if (! parent::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        if (! $id) {
            throw new BaseException('Не указан ID статьи.');
        }

        // проверяем существование статьи перед ее редактированием
        if (! $this->checkArticleExist($id)) {
            throw new BaseException("Статьи с ID = $id не существует.");
        }

        $this->validateArticle();

        // если ЧПУ передан, проверяем, существует ли он
        if ($this->article->url) {
            // если ссылка уже существует, но закреплена за другим элементом
            $article = $this->getArticle($id);
            if (Link::checkExistence($this->article->url) && $article->url != $this->article->url) {
                throw new BaseException('Данная ссылка уже занята.');
            }
        }

        $db = Db::getConnection();
        $sql = 'UPDATE articles 
                SET title = :title,
                    content = :content,
                    category = :category,
                    description = :description,
                    keywords = :keywords
                WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $this->article->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $this->article->content, PDO::PARAM_STR);
        $stmt->bindParam(':category', $this->article->category, PDO::PARAM_INT);
        $stmt->bindParam(':description', $this->article->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $this->article->keywords, PDO::PARAM_STR);
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
            if ($linkCheckExist->link_id != null && $this->article->url) {
                $db = Db::getConnection();
                $sql = 'UPDATE routes SET url = :url WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $this->article->url, PDO::PARAM_STR);
                $stmt->bindParam(':id', $linkCheckExist->link_id, PDO::PARAM_INT);
                $stmt->execute();
                //если ссылка не существует, но новая ссылка была передана, выполняем INSERT и связываем
                // с таблицей статей
            } else if ($linkCheckExist->link_id == null && $this->article->url) {
                $db = Db::getConnection();
                $internal_route = "site/article/$id";
                $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':url', $this->article->url, PDO::PARAM_STR);
                $stmt->bindParam(':internal_route', $internal_route, PDO::PARAM_STR);
                $stmt->execute();
                $linkID = $db->lastInsertId();

                $sql = 'UPDATE articles SET link_id = :link_id WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':link_id', $linkID, PDO::PARAM_INT);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                // если ссылка существует, но новая ссылка не была передана, выполняем DELETE
            } else if ($linkCheckExist->link_id != null && !$this->article->url) {
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
    public function deleteArticle($token, $id)
    {
        if (! parent::checkAccess($token)) {
            throw new BaseException('Доступ запрещен.');
        }

        if (! $id) {
            throw new BaseException('Не указан ID статьи.');
        }

        // проверяем существование статьи перед ее редактированием
        if (! self::checkArticleExist($id)) {
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
    public function searchArticles($search)
    {
        if (strlen($search) < 2) {
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
    public function checkArticleExist($id)
    {
        if (! $id) {
            throw new BaseException('Не указан id статьи.');
        }

        $db = Db::getConnection();
        $sql = 'SELECT id, title FROM articles WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        $article = $stmt->fetch();

        if ($article) {
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
        $result = $db->query('SELECT COUNT(*) FROM articles');
        $quantity = $result->fetch();

        if ($quantity) {
            return (int)$quantity[0];
        }

        return 0;
    }

    public function validateArticle()
    {
        $article = $this->article;

        if (! isset($article->title) || strlen($article->title) < 2) {
            throw new BaseException('Заголовок новости слишком короткий.');
        }

        if (! isset($article->content) || strlen($article->content) < 5) {
            throw new BaseException('Содержимое статьи слишком короткое.');
        }

        if (! isset($article->category) || strlen(trim($article->category)) == 0) {
            $article->category = 0;
        }
        if (! isset($article->url)) {
            $article->url = false;
        }
        if (! isset($article->description)) {
            $article->description = false;
        }
        if (! isset($article->keywords)) {
            $article->keywords = false;
        }
        if (isset($article->url)) {
            $article->url = trim($article->url);
        }
        return $article;
    }
}
