<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 12.08.2018 22:55
 */

namespace Angryjack\models;

use Angryjack\exceptions\BaseException;
use \PDO;

class Site
{
    /**
     * Получаем данные из POST
     * @param bool $assoc тип возращаемого значения
     * @return mixed
     */
    public static function getData($assoc = false)
    {
        $data = file_get_contents('php://input');
        return json_decode($data, $assoc);
    }

    /**
     * Метод входа в админ панель
     * @param $login - логин
     * @param $passwd - пароль
     * @return string - при успешном входе возвращает сгенерированный токен
     * @throws BaseException - все ошибки при процедуре входа
     * @throws \Exception - ошибка при генерации токена
     */
    public static function login($login, $passwd)
    {
        if (! $login) {
            throw new BaseException('Введите логин.');
        }
        if (! $passwd) {
            throw new BaseException('Введите пароль.');
        }

        $user = self::checkUserExist($login);

        if (password_verify($passwd, $user->passwd)) {
            return self::generateToken($login);

        } else {
            throw new BaseException('Указан неверный логин или пароль.');
        }
    }

    /**
     * Проверяем пользователя на существование
     * @param $login - логин пользователя
     * @return mixed - массив с данными пользователя
     * @throws BaseException - если пользователь не найден
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

        if (! $user) {
            throw new BaseException('Пользователь не найден!');
        }
        return $user;
    }

    /**
     * Метод генерации токена
     * @param $login - логин пользователя которому нужно сгенерировать токен
     * @return string - возвращаем сгенерированный токен
     * @throws \Exception - ошибка при генерации токена
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
        throw new \Exception('Не удалось сгенерировать токен.');
    }

    /**
     * Метод проверки уровня доступа
     * @param $token - токен пользователя
     * @return bool - результат проверки прав
     * @throws
     */
    public static function checkAccess($token)
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
        return false;
    }

    /**
     * Получаем токен пользователя из кук
     * Метод нужен для проверки доступа перед отображением страниц админ панели
     * @return string|bool возращает либо токен либо false если токена нет
     */
    public static function getTokenFromCookie()
    {
        if (isset($_COOKIE['token'])) {
            return $_COOKIE['token'];

        }
        return false;
    }

    /**
     * Заменяем из html сущностей теги code, pre, a, img для нормального отображения на странице
     * все останольно должно попадать под правила htmlspecialchars
     * @param $content
     * @return null|string|string[]
     */
    public static function replaceTags($content)
    {
        $patterns[0] = '/&lt;pre&gt;&lt;code class=&quot;\w{1,5}&quot;&gt;/';
        $patterns[1] = '/&lt;\/code&gt;&lt;\/pre&gt;/';
        $patterns[2] = '/&lt;a\s.*?href=&quot;(.+?)&quot;.*?&gt;(.+?)&lt;\/a&gt;*/';

        $replacements[0] = '<pre><code class="$1">';
        $replacements[1] = '</code></pre>';
        $replacements[2] = '<a href="$1">$2</a>';

        return preg_replace($patterns, $replacements, $content);
    }

    /**
     * Возвращаем случайный цвет
     * @return mixed
     */
    public static function randBgColor()
    {
        $colors = [
            '#8a2b2baa',
            '#8a2b6daa',
            '#4b2b8ac2',
            '#2b488aaa',
            '#2b678aaa',
            '#2b8a86aa',
            '#2b8a5eaa',
            '#3e8a2baa',
            '#898a2baa'
        ];
        return $colors[array_rand($colors)];
    }
}