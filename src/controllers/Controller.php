<?php
/**
 * Created by angryjack
 * Date: 2018-12-19 23:19
 */

namespace Angryjack\controllers;

use Angryjack\helpers\Request;
use Angryjack\helpers\Token;

class Controller
{
    use Token, Request;

    /**
     * @param $data
     */
    public function printJson($data)
    {
        echo json_encode($data);
    }
}
