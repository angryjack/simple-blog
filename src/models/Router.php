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
                // nothing yet
            } elseif ($result === true) {
                echo json_encode('success.');
            } else {
                echo json_encode('error.');
            }
        } catch (BaseException $e) {
            echo json_encode($e->getMessage());
        } catch (\Exception $e) {
            die('error here!');

            file_put_contents(__DIR__ . '/../../errors.txt', $e->getMessage() . PHP_EOL);
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
            $internalRoute = preg_replace("~$uriPattern~", $path, $this->url);
            $segments = explode('/', $internalRoute);

            $controllerName = 'Angryjack\controllers\\' . ucfirst(array_shift($segments) . 'Controller');
            $controller = new $controllerName;
            $method = array_shift($segments);
            $params = $segments;

            return call_user_func_array(array($controller, $method), $params);
        }
        throw new BaseException('Запрошенной страницы не существует.');
    }
}
