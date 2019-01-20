<?php
/**
 * Created by angryjack
 * Date: 2018-12-16 21:45
 */

namespace Angryjack\helpers;

use Angryjack\exceptions\BaseException;
use Angryjack\models\Db;
use PDO;

trait Link
{
    /**
     * Проверяем существование ЧПУ
     * @param $url
     * @return array
     * @throws \Exception
     */
    public function checkLinkExistence($url) : array
    {
        $db = Db::getConnection();
        $sql = 'SELECT id, url FROM routes WHERE url = :url';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_OBJ);
        $stmt->execute();
        return $stmt->fetch();
    }

    /**
     * @param $url
     * @param $internalRoute
     * @return int ИД созданной сслыки
     * @throws BaseException
     */
    public function createLink($url, $internalRoute) : int
    {
        if (self::checkLinkExistence($url)) {
            throw new BaseException('Ссылка уже существует.');
        }

        $db = Db::getConnection();
        $sql = 'INSERT INTO routes (url, internal_route) VALUES (:url, :internal_route)';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':internal_route', $internalRoute, PDO::PARAM_STR);
        $stmt->execute();

        return $db->lastInsertId();
    }

    /**
     * Обновление сслыки
     * @param $url
     * @param $id
     * @return int
     * @throws \Exception
     */
    public function updateLink($url, $id) : int
    {
        $db = Db::getConnection();
        $sql = 'UPDATE routes SET url = :url WHERE id = :id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':url', $url, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Удаление сслыки
     * @param $id
     * @return bool
     * @throws \Exception
     */
    public function deleteLink($id) : bool
    {
        $db = Db::getConnection();
        $sql = 'DELETE FROM routes WHERE id = :link_id';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':link_id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
