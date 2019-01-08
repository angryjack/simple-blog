<?php
/**
 * Created by angryjack
 * Date: 2019-01-07 18:10
 */

namespace Angryjack\helpers;

require_once "../../src/autoload.php";

use Angryjack\models\Article;

class ValidateTest extends \PHPUnit_Framework_TestCase
{

    public function testMakeValidation()
    {
        $data = array(
            'test string' => 'str|min:3|max:25',
            123 => 'int|min:50|max:255'
        );
        $article = new Article();

        $this->assertTrue($article->makeValidation($data));

        $data2 = array(
            'test string' => 'int|min:3|max:25',
            123 => 'str|min:50|max:255'
        );

        $this->assertFalse($article->makeValidation($data2));
    }
}
