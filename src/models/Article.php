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
class Article extends Model implements Action
{
    public $article;

    public function __construct()
    {
        //
    }

    /**
     * Получаем все статьи
     * @param int $page
     * @return array|null
     * @throws \Exception
     */
    public function showAll($page = 1): ?array
    {
        $page = intval($page);
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $db = Db::getConnection();

        $sql = 'SELECT articles.id AS "id",
                       articles.title AS "title",
                       articles.content AS "content",                       
                       categories.title AS "category",
                       categories.id AS "category_id",                
                       (SELECT url FROM routes WHERE routes.id = categories.link_id) AS "category_link",
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

        return $stmt->fetchAll();
    }

    /**
     * Получаем конкретную статью
     * @param $id
     * @return array|null
     * @throws \Exception
     */
    public function show($id): ?object
    {
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

        return $stmt->fetch();
    }

    /**
     * Создаем статью
     * @param array $data
     * @return bool
     * @throws BaseException
     */
    public function store(array $data): bool
    {
        parent::makeValidation([
            $data->title => 'str',
            $data->content => 'str',
            $data->category_id => 'int',
            $data->description => 'str',
            $data->keywords => 'str',
            $data->url => 'str',
        ]);

        $db = Db::getConnection();
        $sql = 'INSERT INTO articles (title, content, category, description, keywords, create_date) 
                VALUES (:title, :content, :category, :description, :keywords, UNIX_TIMESTAMP())';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $data->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $data->content, PDO::PARAM_STR);
        $stmt->bindParam(':category', $data->category, PDO::PARAM_INT);
        $stmt->bindParam(':description', $data->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $data->keywords, PDO::PARAM_STR);
        $stmt->execute();

        if (! empty($data->url)) {
            $articleId = $db->lastInsertId();
            $linkId = parent::createLink($data->url, "site/article/$articleId");

            $sql = 'UPDATE articles SET link_id = :link_id WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':link_id', $linkId, PDO::PARAM_INT);
            $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
            $stmt->execute();
        }
        return true;
    }

    /**
     * Редактируем статью
     * @param $id
     * @param array $data
     * @return bool
     * @throws BaseException
     */
    public function update($id, array $data): bool
    {
        parent::makeValidation([
            $data->title => 'str',
            $data->content => 'str',
            $data->category_id => 'int',
            $data->description => 'str',
            $data->keywords => 'str',
            $data->url => 'str',
        ]);

        $db = Db::getConnection();
        $sql = 'UPDATE articles 
                SET title = :title,
                    content = :content,
                    category = :category,
                    description = :description,
                    keywords = :keywords
                WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $data->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $data->content, PDO::PARAM_STR);
        $stmt->bindParam(':category', $data->category, PDO::PARAM_INT);
        $stmt->bindParam(':description', $data->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $data->keywords, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if (! empty($data->url)) {
            $article = $this->get($id);

            $link = self::checkLinkExistence($data->url);

            if ($link && $article->url != $data->url) {
                throw new BaseException('Данная ссылка уже занята.');
            }

            if ($link == null) {
                $linkId = parent::createLink($data->url, "site/article/$id");

                $sql = 'UPDATE articles SET link_id = :link_id WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':link_id', $linkId, PDO::PARAM_INT);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } elseif (empty($data->url)) {
                $db = Db::getConnection();
                $sql = 'DELETE FROM routes WHERE id = (SELECT link_id FROM articles WHERE id = :id)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $sql = 'UPDATE articles SET link_id = NULL WHERE id = :id';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                parent::updateLink($data->url, $link->id);
            }
        }

        return true;
    }

    /**
     * Удаляем статью
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id): bool
    {
        $article = $this->get($id);

        if (! empty($article->url)) {
            parent::deleteLink($article->url);
        }

        $db = Db::getConnection();
        $sql = 'DELETE FROM articles WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Поиск по статьям
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    public function search(array $data): ?array
    {
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
        $stmt->bindValue(':search', "%$data%", PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
