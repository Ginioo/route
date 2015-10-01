<?php
/**
 * Application
 *
 * @author  Gino Wu
 * @since   2015.10.01
 * @version v1
 */
use \Sandbox\Event as Event;

class Application extends Event
{
    /**
     * POST   C
     * GET    R
     * PUT    U
     * DELETE D
     * @return $requestMethod CRUD
     */
    public function getRequestEvent()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'DELETE':
                $requestMethod = 'delete';
                break;
            case 'POST':
                $requestMethod = 'create';
                break;
            case 'PUT':
                $requestMethod = 'update';
                break;
            case 'GET':
                $requestMethod = 'retrieve';
                break;
            default:
                $requestMethod = '';
                break;
        }

        return $requestMethod;
    }
    
    /**
     * 
     * @return $input 傳入的參數
     */
    public function getInputData()
    {
        if ('POST' === $_SERVER['REQUEST_METHOD']) {
            $input = $_POST;
        } else {
            parse_str($_SERVER['QUERY_STRING'], $input);
        }

        return $input;
    }
}
