<?php
/**
 * Created by angryjack
 * Date: 2018-12-23 15:00
 */

namespace Angryjack\models;

use Angryjack\helpers\Link;
use Angryjack\helpers\Request;
use Angryjack\helpers\Token;

abstract class Model
{
    use Link, Request, Token;
}
