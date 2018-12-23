<?php
/**
 * Created by angryjack
 * Date: 2018-12-21 23:47
 */

namespace Angryjack\models;

use PDO;

class User extends Model
{
    /**
     * Метод входа в админ панель
     * @param $login - логин
     * @param $passwd - пароль
     * @return string - при успешном входе возвращает сгенерированный токен
     * @throws \Exception - ошибка при генерации токена
     */
    public function login($login, $passwd)
    {
        $user = self::checkUserExist($login);

        if (password_verify($passwd, $user->passwd)) {
            return parent::generateToken($login);
        }
        return null;
    }

    /**
     * Проверяем пользователя на существование
     * @param $login - логин пользователя
     * @return mixed - массив с данными пользователя
     * @throws \Exception
     */
    public function checkUserExist($login)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM users WHERE login = :login';
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_OBJ);
        $result->execute();
        $user = $result->fetch();

        return $user;
    }
}
