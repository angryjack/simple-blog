<?php
/**
 * Created by angryjack
 * Date: 2018-12-21 22:10
 */

namespace Angryjack\helpers;

use Angryjack\models\Db;
use PDO;

trait Token
{
    /**
     * Метод генерации токена
     * @param $login - логин пользователя которому нужно сгенерировать токен
     * @return string - возвращаем сгенерированный токен
     * @throws \Exception - ошибка при генерации токена
     */
    protected function generateToken($login)
    {
        $token = md5(mt_rand());

        $db = Db::getConnection();
        $sql = 'UPDATE users SET token = :token WHERE login = :login';
        $result = $db->prepare($sql);
        $result->bindParam(':token', $token, PDO::PARAM_STR);
        $result->bindParam(':login', $login, PDO::PARAM_STR);

        if ($result->execute()) {
            return $token;
        }
        throw new \Exception('Не удалось сгенерировать токен.');
    }

    /**
     * Метод проверки уровня доступа
     * @param $token - токен пользователя
     * @return bool - результат проверки прав
     * @throws
     */
    public function checkAccess($token)
    {
        if ($token) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM users WHERE token = :token';
            $result = $db->prepare($sql);
            $result->bindParam(':token', $token, PDO::PARAM_STR);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();
            $user = $result->fetch();

            if ($user) {
                //todo сделать проверку роли пользователя
                return true;
            }
        }
        header('Location: /admin/login');
        die('Access denied.');
    }

    /**
     * Получаем токен пользователя из кук
     * Метод нужен для проверки доступа перед отображением страниц админ панели
     * @return string|bool возращает либо токен либо false если токена нет
     */
    public function getTokenFromCookie()
    {
        if (isset($_COOKIE['token'])) {
            return $_COOKIE['token'];

        }
        return false;
    }
}
