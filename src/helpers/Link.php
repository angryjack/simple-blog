<?php
/**
 * Created by angryjack
 * Date: 2018-12-16 21:45
 */

namespace Angryjack\helpers;

use Angryjack\models\Db;

trait Link
{
    /**
     * Проверяем существование ЧПУ
     * @param $url
     * @return mixed
     * @throws \Exception
     */
    public static function checkExistence($url)
    {
        $db = Db::getConnection();
        $sql = 'SELECT url FROM routes WHERE url = :url';
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':url', $url, \PDO::PARAM_STR);
        $stmt->setFetchMode(\PDO::FETCH_OBJ);
        $stmt->execute();
        return $stmt->fetch();
    }
}
