<?php
/**
 * Application
 *
 * @author  Gino Wu
 * @since   2015.10.01
 * @version v2
 */
namespace Sandbox;

class Application extends Event
{
    /**
     * Mapping crud to event name
     * POST:create
     * GET:retrieve
     * PUT:update
     * DELETE:delete
     *
     * @param void
     * @return string $eventName
     */
    public function getRequestEvent()
    {
        $aRequestUri = explode('?', $_SERVER['REQUEST_URI']);
        $requestUri = $aRequestUri[0];
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'DELETE':
                $eventName = 'delete';
                break;
            case 'POST':
                $eventName = 'create';
                break;
            case 'PUT':
                $eventName = 'update';
                break;
            case 'GET':
                $eventName = 'retrieve';
                break;
            default:
                $eventName = '';
                break;
        }

        return $eventName . $requestUri;
    }

    /**
     * get input value
     *
     * @param void
     * @return array $input
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

    /**
     * post
     *
     * @param string $route
     * @param string $controllerClass
     * @param string $method
     * @return void
     */
    public function post($route, $controllerClass, $method)
    {
        $this->on('create/' . $route, $this->deligate($controllerClass, $method));
    }

    /**
     * get
     *
     * @param string $route
     * @param string $controllerClass
     * @param string $method
     * @return void
     */
    public function get($route, $controllerClass, $method)
    {
        $this->on('retrieve/' . $route, $this->deligate($controllerClass, $method));
    }

    /**
     * put
     *
     * @param string $route
     * @param string $controllerClass
     * @param string $method
     * @return void
     */
    public function put($route, $controllerClass, $method)
    {
        $this->on('update/' . $route, $this->deligate($controllerClass, $method));
    }

    /**
     * delete
     *
     * @param string $route
     * @param string $controllerClass
     * @param string $method
     * @return void
     */
    public function delete($route, $controllerClass, $method)
    {
        $this->on('delete/' . $route, $this->deligate($controllerClass, $method));
    }

    /**
     * debug
     *
     * @param string $callback
     * @return void
     */
    public function debug($callback)
    {
        $this->on('debug', $callback);
    }

    /**
     * deligate
     *
     * @param string $controllerClass
     * @param string $method
     * @return void
     */
    private function deligate($controllerClass, $method)
    {
        if (!class_exists($controllerClass)) {
            throw new Exception('class:' . $controllerClass . 'does not exist');
        }

        return function ($input) use ($controllerClass, $method) {
            $oController = new $controllerClass();

            if (!method_exists($oController, $method)) {
                throw new Exception('method:' . $method . 'does not exist in class' . $controllerClass);
            } else {
                $oController->{$method}($input);
            }
        };
    }
}
