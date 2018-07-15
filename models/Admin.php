<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 14:10
 */

class Admin
{
    /**
     * Проверка прав доступа
     * @param $token - токен пользователя
     * если токен не задан, берем токен из куки
     * @return bool - если проверка прошла успешно возвращаем true
     * иначе переводим на страницу входа в админ панель
     */
    public static function checkAccess($token = false)
    {

        if (!$token) {
            if (isset($_COOKIE['token'])) {
                $token = $_COOKIE['token'];
            } else {
                $token = null;
            }
        }

        if (isset($token)) {
            $db = Db::getConnection();
            $sql = 'SELECT * FROM users WHERE token = :token';
            $result = $db->prepare($sql);
            $result->bindParam(':token', $token, PDO::PARAM_STR);
            $result->setFetchMode(PDO::FETCH_OBJ);
            $result->execute();
            $user = $result->fetch();
            $db = $sql = $result = null;

            if ($user) {
                //todo сделать проверку роли пользователя
                return true;
            }
        }

        header('Location: /admin/login');
        return false;
    }

    /**
     * Создание пользователя
     * @param $login
     * @param $email
     * @param $passwd - если пароль не задан, он будет сгенерирован
     * @return bool
     */
    public static function createUser($login, $email, $passwd = false)
    {
        if(!self::checkAccess()){
            die(ACCESS_DENIED);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die(CREATE_USER_ERROR_INVALID_EMAIL_FORMAT);
        }

        if(self::checkUserExist($login)){
            die(CREATE_USER_ERROR_USER_ALREADY_EXIST);
        }

        if (!$passwd) {
            $passwd = substr(md5(mt_rand()), -8);
        }

        $passwd = password_hash($passwd, PASSWORD_DEFAULT);

        $db = Db::getConnection();
        $sql = 'INSERT INTO users (login, email, passwd) VALUES (:login, :email, :passwd)';
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':passwd', $passwd, PDO::PARAM_STR);

        if ($result->execute()) {
            return true;
        }

        return false;
    }

    /**
     * Проверка логина на существование
     * @param $login - логин пользователя
     * @return bool - true - пользователь существует, false - нет
     */
    public static function checkUserExist($login)
    {
        $db = Db::getConnection();
        $sql = 'SELECT * FROM users WHERE login = :login';
        $result = $db->prepare($sql);
        $result->bindParam(':login', $login, PDO::PARAM_STR);
        $result->setFetchMode(PDO::FETCH_OBJ);
        $result->execute();
        $user = $result->fetch();

        if($user){
            return $user;
        }
        return false;
    }

    /**
     * Метод входа в админ панель
     * @param $login
     * @param $passwd
     * @return bool|string - если вход выполнен успешно, возвращаем сгенерированный токен
     * если вход неудачный - false
     */
    public static function login($login, $passwd)
    {
        $user = self::checkUserExist($login);

        if ($user && password_verify($passwd, $user->passwd)) {

            return self::generateToken($login);
        }

        return false;
    }

    /**
     * Приватный метод генерации токена
     * @param $login - логин пользователя, которому генерируем токен
     * @return bool|string - если успешно - возвращаем токен
     * если ошибка - false
     */
    private static function generateToken($login)
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
        return false;
    }
}