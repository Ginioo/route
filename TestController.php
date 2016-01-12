<?php
/**
 * TestController
 *
 * @author  Gino Wu
 * @since   2015.10.23
 * @version v1
 */
namespace Ginioo\Sandbox;

class TestController
{
    public function test($input)
    {
        echo __CLASS__ . '::' . __FUNCTION__;
    }

    public function test2($input)
    {
        echo __CLASS__ . '::' . __FUNCTION__;
        echo $path = dirname(dirname(dirname(__DIR__))) . '/EstBadminton/view/index.html';
        require($path);
    }

    public function index($input)
    {
        $path = dirname(dirname(dirname(__DIR__))) . '/EstBadminton/view/index.html';
        require($path);
    }

    public function member($input)
    {
        $path = dirname(dirname(dirname(__DIR__))) . '/EstBadminton/view/member.html';
        require($path);
    }

    public function passbook($input)
    {
        $path = dirname(dirname(dirname(__DIR__))) . '/EstBadminton/view/passbook.html';
        require($path);
    }

    public function admin($input)
    {
        $path = dirname(dirname(dirname(__DIR__))) . '/EstBadminton/view/admin.html';
        require($path);
    }
}
