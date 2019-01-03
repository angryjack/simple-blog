<?php
/**
 * Created by angryjack
 * Date: 2019-01-02 14:13
 */

namespace Angryjack\models;

class RouterTest extends \PHPUnit\Framework\TestCase
{
    public function test__construct()
    {
        $stack = (object)[1];
        $this->assertEmpty($stack);
    }

    public function testRun()
    {
    //
    }
}
