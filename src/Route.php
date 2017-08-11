<?php
/**
 * Application
 *
 * @author  Gino Wu
 * @since   2015.10.01
 * @version v2
 */
namespace Ginioo\Sandbox;

use \Exception;

class Route extends Event
{
    const CREATE = 'POST';
    const RETRIEVE = 'GET';
    const UPDATE = 'PUT';
    const DELETE = 'DELETE';

    private $groupKey = '';

    /**
     * Mapping crud to event name
     *
     * @param void
     * @return string $eventName
     */
    public function getRequestRoute()
    {
        $aRequestUri = explode('?', $_SERVER['REQUEST_URI']);
        $requestUri = $aRequestUri[0];
        switch ($_SERVER['REQUEST_METHOD']) {
            case self::CREATE:
                $eventName = self::CREATE;
                break;
            case self::RETRIEVE:
                $eventName = self::RETRIEVE;
                break;
            case self::UPDATE:
                $eventName = self::UPDATE;
                break;
            case self::DELETE:
                $eventName = self::DELETE;
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
        $input = array();
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $input);
        }

        if ($_SERVER['REQUEST_METHOD'] === self::CREATE) {
            $input = $_POST;
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
        $this->on(self::CREATE . "/{$this->groupKey}/{$route}", $this->deligate($controllerClass, $method));
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
        $this->on(self::RETRIEVE . "/{$this->groupKey}/{$route}", $this->deligate($controllerClass, $method));
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
        $this->on(self::UPDATE . "/{$this->groupKey}/{$route}", $this->deligate($controllerClass, $method));
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
        $this->on(self::DELETE . "/{$this->groupKey}/{$route}", $this->deligate($controllerClass, $method));
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
            throw new Exception("class: {$controllerClass} does not exist");
        }

        return function ($parameters) use ($controllerClass, $method) {
            $oController = new $controllerClass();

            if (!method_exists($oController, $method)) {
                throw new Exception("method: {$method} does not exist in class: {$controllerClass}");
            } else {
                call_user_func(array($oController, $method), $parameters);
            }
        };
    }

    /**
     * group
     *
     * @param string $groupKey
     * @param string $callback
     * @return this
     */
    public function group($groupKey, $callback)
    {
        if (!is_callable($callback, true)) {
            throw new Exception("Oops! Something went wrong when grouping routes.");
        }

        $this->groupKey .= ($this->groupKey === '') ? "$groupKey" : "/{$groupKey}";
        $callback();
    }

    /**
     * To check if route has set or not
     *
     * @param string $name 事件名稱
     * @return boolean
     */
    public function hasRoute($route)
    {
        return $this->hasEvent($route);
    }
    
//     public function getResource()
//     {
//         $subject = $_SERVER['REQUEST_URI'];
//         $pattern = '/.(css|js|jpeg|png)$/';
//         preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE);
//         if (isset($matches[0])) {
//             // $subject = dirname(dirname(dirname(__DIR__))) . $subject;
//             // header("Content-Type: text/{$matches[1][0]}; charset=UTF-8");
//             // echo file_get_contents($subject);
//             return array("Content-Type: text/{$matches[1][0]}; charset=UTF-8", $subject);
//         }
//     }

}
