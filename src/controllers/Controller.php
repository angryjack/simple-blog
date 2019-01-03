<?php
/**
 * Created by angryjack
 * Date: 2018-12-19 23:19
 */

namespace Angryjack\controllers;

use Angryjack\helpers\Request;
use Angryjack\helpers\Token;

abstract class Controller
{
    use Token, Request;

    /**
     * Подключаем необходимый шаблон
     * @param $template
     * @param array $data
     * @return $this
     */
    public function view($template, array $data = [])
    {
        $path = implode('/', explode('.', $template));

        // подключаем шапку
        require_once(__DIR__ . '/../views/site/layouts/header.php');
        // основной контент
        require_once(__DIR__ . '/../views/' . $path . '.php');
        // подвал
        require_once(__DIR__ . '/../views/site/layouts/footer.php');

        return $this;
    }
}
