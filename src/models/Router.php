<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:51
 */

namespace Angryjack\models;

use PDO;

class Router
{
    private $routes;

    /**
     * Конструктор
     */
    public function __construct()
    {
        // Путь к файлу с роутами
        $routesPath = ROOT . '/src/includes/routes.php';

        //получаем строку запроса
        $uri = $this->getURI();

        // назвачаем флаг если строка запроса начинается с install
        $flag = preg_match('/^install/', $uri);

        // если запрос не на установку и нет параметров у базы данных, то считаем что сайт не установлен
        if (! $flag && ! file_exists('../src/includes/db_params.php')) {
            header('Location: /install');
            exit('Cайт не установлен. Вы будете перенаправлены на страницу установки.');
        }
        //todo подумать над тем, что на установленном сайте можно перейти к установщику
        if ($flag) {
            $this->routes = include($routesPath);
        } else {
            $this->routes = array_merge($this->getRoutesFromDB(), include($routesPath));
        }
    }

    /**
     * Возвращает строку запроса
     */
    private function getURI()
    {
        if (! empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
        return false;
    }

    public function getRoutesFromDB()
    {
        $db = Db::getConnection();
        $stmt = $db->query('SELECT url, internal_route FROM routes');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $routes = $stmt->fetchAll();

        $dbRoutes = [];
        foreach ($routes as $route) {
            $dbRoutes[$route['url']] = $route['internal_route'];
        }

        return $dbRoutes;
    }

    /**
     * Метод для обработки запроса
     */
    public function run()
    {
        $uri = $this->getURI();

        foreach ($this->routes as $uriPattern => $path) {
            if (! preg_match("~$uriPattern~", $uri)) {
                continue;
            }
            $internalRoute = preg_replace("~$uriPattern~", $path, $uri);
            $segments = explode('/', $internalRoute);
            $controllerName = array_shift($segments) . 'Controller';
            $controllerName = ucfirst($controllerName);
            $actionName = 'action' . ucfirst(array_shift($segments));
            $controllerName = 'Angryjack\controllers\\' . $controllerName;
            $controllerObject = new $controllerName;

            try {
                $result = call_user_func_array(array($controllerObject, $actionName), $segments);
            } catch (\Exception $e) {
                $result = array(
                    $e->getMessage()
                );
            }
            if (empty($result)) {
                continue;
            } elseif (is_array($result)) {
                echo json_decode($result);
            } else {
                die('ok!');
            }
            break;
        }
    }
}
