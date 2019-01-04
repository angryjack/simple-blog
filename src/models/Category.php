<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 29.07.2018 20:32
 */

namespace Angryjack\models;

use Angryjack\exceptions\BaseException;
use PDO;

class Category extends Model implements Action
{
    public function __construct($category = false)
    {
        //
    }

    /**
     * @param int $page
     * @return array|null
     * @throws \Exception
     */
    public function showAll($page = 1): ?array
    {
        $page = intval($page);
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

        return $stmt->fetchAll();
    }

    /**
     * @param $id
     * @return array|null
     * @throws \Exception
     */
    public function show($id): ?object
    {
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

        return $stmt->fetch();
    }

    /**
     * @param array $data
     * @return bool
     * @throws BaseException
     */
    public function store(array $data): bool
    {
        parent::makeValidation([
            $data->title => 'str',
            $data->content => 'str',
            $data->description => 'str',
            $data->keywords => 'str',
            $data->url => 'str',
        ]);

        $db = Db::getConnection();
        $sql = 'INSERT INTO categories (title, content, description, keywords) VALUES (:title, :content, :description, :keywords)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $data->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $data->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $data->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $data->keywords, PDO::PARAM_STR);
        $stmt->execute();

        if (! empty($data->url)) {
            $categoryId = $db->lastInsertId();
            $linkId = parent::createLink($data->url, "site/category/$categoryId");

            $sql = 'UPDATE categories SET link_id = :link_id WHERE id = :id';
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':link_id', $linkId, PDO::PARAM_INT);
            $stmt->bindParam(':id', $articleId, PDO::PARAM_INT);
            $stmt->execute();
        }
        return true;
    }

    /**
     * @param $id
     * @param array $data
     * @return bool
     * @throws BaseException
     */
    public function update($id, array $data): bool
    {
        parent::makeValidation([
            $id => 'int',
            $data->title => 'str',
            $data->content => 'str',
            $data->description => 'str',
            $data->keywords => 'str',
            $data->url => 'str',
        ]);

        $db = Db::getConnection();
        $sql = 'UPDATE categories 
                SET title = :title, content = :content, description = :description, keywords = :keywords
                WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $data->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $data->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $data->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $data->keywords, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        if (! empty($data->url)) {
            $category = $this->get($id);

            $link = self::checkLinkExistence($data->url);

            if ($link && $category->url != $data->url) {
                throw new BaseException('Данная ссылка уже занята.');
            }

            if ($link == null) {
                $linkId = parent::createLink($data->url, "site/category/$id");

                $sql = 'UPDATE categories SET link_id = :link_id WHERE id = :id';
                $db = Db::getConnection();
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':link_id', $linkId, PDO::PARAM_INT);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            } elseif (empty($data->url)) {
                $db = Db::getConnection();
                $sql = 'DELETE FROM routes WHERE id = (SELECT link_id FROM categories WHERE id = :id)';
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();

                $sql = 'UPDATE categories SET link_id = NULL WHERE id = :id';
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
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id): bool
    {
        $db = Db::getConnection();
        $sql = 'UPDATE articles SET category = 0 WHERE category = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $item = $this->get($id);

        if (! empty($item->url)) {
            parent::deleteLink($item->url);
        }

        $sql = 'DELETE FROM articles WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * @param array $data
     * @return array|null
     * @throws \Exception
     */
    public function search(array $data): ?array
    {
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
        $stmt->bindValue(':search', "%$data%", PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
