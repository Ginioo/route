<?php
/**
 * TestController
 *
 * @author  Gino Wu
 * @since   2015.10.23
 * @version v1
 */
namespace Sandbox;

class TestController
{
    public function test($input)
    {
        echo __CLASS__ . '::' . __FUNCTION__;
    }

    public function test2($input)
    {
        echo __CLASS__ . '::' . __FUNCTION__;
    }
}
