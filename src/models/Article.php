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
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $sql = 'SELECT articles.id AS "id",
                       articles.title AS "title",
                       articles.content AS "content"                       
                FROM articles
                ORDER BY id DESC
                LIMIT :limit OFFSET :offset';

        $db = Db::getConnection();
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
        $sql = 'SELECT articles.id AS "id",
                       articles.title AS "title",
                       articles.content AS "content",
                       articles.description AS "description",
                       articles.keywords AS "keywords"
                FROM articles
                WHERE articles.id = :id';

        $db = Db::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Создаем статью
     * @param object $data
     * @return bool
     * @throws \Exception
     */
    public function store(object $data): bool
    {
        $article = $data->article;

        $this->makeValidation([
            //
        ]);

        $sql = 'INSERT INTO articles (title, content, description, keywords, create_date) 
                VALUES (:title, :content, :description, :keywords, UNIX_TIMESTAMP())';

        $db = Db::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $article->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $article->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $article->metaDescription, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $article->metaKeywords, PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Редактируем статью
     * @param $id
     * @param object $data
     * @return bool
     * @throws \Exception
     */
    public function update($id, object $data): bool
    {
        $article = $data->article;

        $this->makeValidation([
            //$article->title => 'str',
        ]);

        $sql = 'UPDATE articles 
                SET title = :title,
                    content = :content,
                    description = :description,
                    keywords = :keywords
                WHERE id = :id';

        $db = Db::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':title', $article->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $article->content, PDO::PARAM_STR);
        $stmt->bindParam(':description', $article->description, PDO::PARAM_STR);
        $stmt->bindParam(':keywords', $article->keywords, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Удаляем статью
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function destroy($id): bool
    {
        $sql = 'DELETE FROM articles WHERE id = :id';

        $db = Db::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Поиск по статьям
     * @param object $data
     * @return array|null
     * @throws \Exception
     */
    public function search(object $data): ?array
    {
        $this->makeValidation([
            //$data->search
        ]);

        $sql = 'SELECT articles.id AS "id",
                       articles.title AS "title",
                       articles.content AS "content"
                FROM articles
                WHERE articles.title LIKE :search
                ORDER BY title ASC';

        $db = Db::getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':search', "%$data->search%", PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}
