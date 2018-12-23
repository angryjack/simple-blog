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

    public function view($template, array $data = [])
    {
        return true;
    }
}
