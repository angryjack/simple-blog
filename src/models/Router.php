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

        $uri = $this->getURI();

        $flag = preg_match('/^install/', $uri);

        if (! $flag && ! file_exists('../src/includes/db_params.php')){
            header('Location: /install');
            exit ('Cайт не установлен. Вы будете перенаправлены на страницу установки через 3 секунды.');
        }
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
    }

    public function getRoutesFromDB(){
        $db = Db::getConnection();
        $stmt = $db->query('SELECT url, internal_route FROM routes');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $routes = $stmt->fetchAll();

        $dbRoutes = [];
        foreach ($routes as $route){
            $dbRoutes[$route['url']] = $route['internal_route'];
        }

        return $dbRoutes;
    }

    /**
     * Метод для обработки запроса
     */
    public function run()
    {
        // Получаем строку запроса
        $uri = $this->getURI();

        // Проверяем наличие такого запроса в массиве маршрутов (routes.php)
        foreach ($this->routes as $uriPattern => $path) {

            // Сравниваем $uriPattern и $uri
            if (preg_match("~$uriPattern~", $uri)) {

                // Получаем внутренний путь из внешнего согласно правилу.
                $internalRoute = preg_replace("~$uriPattern~", $path, $uri);

                // Определить контроллер, action, параметры
                $segments = explode('/', $internalRoute);

                $controllerName = array_shift($segments) . 'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));

                $parameters = $segments;

                $controllerName = 'Angryjack\controllers\\' . $controllerName;

                // Создать объект, вызвать метод (т.е. action)
                $controllerObject = new $controllerName;

                /* Вызываем необходимый метод ($actionName) у определенного
                 * класса ($controllerObject) с заданными ($parameters) параметрами
                 */
                $result = call_user_func_array(array($controllerObject, $actionName), $parameters);

                if ($result != null) {
                    break;
                }
            }
        }
    }

}
