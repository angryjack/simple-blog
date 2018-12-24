<?php
/**
 * Created by angryjack
 * email angryj2ck@gmail.com
 * Date: 24.06.2018 12:51
 */

namespace Angryjack\models;

use Angryjack\controllers\Controller;
use Angryjack\exceptions\BaseException;

class Router
{
    /**
     * @var array
     */
    private $routes;

    /**
     * @var string
     */
    private $url;

    /**
     * Router constructor.
     */
    public function __construct()
    {
        // Путь к файлу с роутами
        $this->routes = include(__DIR__ . '/../includes/routes.php');

        // Строка запроса
        $this->url = trim($_SERVER['REQUEST_URI'], '/');

        //todo проверить на установку
        //todo добавить роуты из БД
    }

    /**
     * Метод для обработки запроса
     */
    public function run()
    {
        try {
            $result = $this->invokeController();

            if (is_array($result)) {
                echo json_encode($result);
            } elseif ($result instanceof Controller) {
                exit;
            } elseif ($result === true) {
                echo json_encode('success.');
            } else {
                echo json_encode('error.');
            }
            exit;
        } catch (BaseException $e){
            echo json_encode($e->getMessage());
        } catch (\Exception $e) {
            file_put_contents('errors.txt', $e->getMessage() . PHP_EOL);
            header('HTTP/1.1 503 Service Temporarily Unavailable');
            header('Status: 503 Service Temporarily Unavailable');
            header('Retry-After: 300');
            include __DIR__ . '/../views/site/error.php';
            die;
        }
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    protected function invokeController()
    {
        foreach ($this->routes as $uriPattern => $path) {
            if (! preg_match("~$uriPattern~", $this->url)) {
                continue;
            }
            $controllerName = ucfirst(array_shift($segments) . 'Controller');
            $controllerName = 'Angryjack\controllers\\' . $controllerName;
            $controllerObject = new $controllerName;

            $actionName = 'action' . ucfirst(array_shift($segments));

            $internalRoute = preg_replace("~$uriPattern~", $path, $this->url);
            $segments = explode('/', $internalRoute);

            return call_user_func_array(array($controllerObject, $actionName), $segments);
        }
        throw new \Exception('Запрошенной страницы не существует.');
    }
}
