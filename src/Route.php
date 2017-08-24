<?php
/**
 * Application
 *
 * @author  Gino Wu
 * @version v3
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
        $uri = $this->getRouteUri($route);
        $routeParameterDefinitionStr = $this->getParameterDefinitionFromRouteString($route);
        $this->on(self::CREATE . "/{$this->groupKey}/{$uri}", $this->deligate($controllerClass, $method, $routeParameterDefinitionStr));
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
        $uri = $this->getRouteUri($route);
        $routeParameterDefinitionStr = $this->getParameterDefinitionFromRouteString($route);
        $this->on(self::RETRIEVE . "/{$this->groupKey}/{$uri}", $this->deligate($controllerClass, $method, $routeParameterDefinitionStr));
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
        $uri = $this->getRouteUri($route);
        $routeParameterDefinitionStr = $this->getParameterDefinitionFromRouteString($route);
        $this->on(self::UPDATE . "/{$this->groupKey}/{$uri}", $this->deligate($controllerClass, $method, $routeParameterDefinitionStr));
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
        $uri = $this->getRouteUri($route);
        $routeParameterDefinitionStr = $this->getParameterDefinitionFromRouteString($route);
        $this->on(self::DELETE . "/{$this->groupKey}/{$uri}", $this->deligate($controllerClass, $method, $routeParameterDefinitionStr));
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
     * @return callable
     */
    protected function deligate($controllerClass, $method, $routeParameterDefinitionStr)
    {
        if (!class_exists($controllerClass)) {
            throw new Exception("class: {$controllerClass} does not exist");
        }

        return function ($routeParameterValueStr, $parameters) use ($controllerClass, $method, $routeParameterDefinitionStr) {
            $routeParameterDefinitionArray = explode('/', $routeParameterDefinitionStr);
            $routeParameterValueArray = explode('/', $routeParameterValueStr);

            $params = array();
            foreach ($routeParameterDefinitionArray as $index => $definitionStr) {
                if (substr($definitionStr, 0, 1) !== ':') {
                    continue;
                }

                if (substr($definitionStr, -1) !== '?' && empty($routeParameterValueArray[$index])) {
                    throw new Exception("Parameter: {" . strtolower(substr($definitionStr, 1)) . "} is required.");
                }
                // $params[] = substr($definitionStr, 1);
                // $params[] = substr($definitionStr, 1, -1);
                $params[] = empty($routeParameterValueArray[$index]) ? null : $routeParameterValueArray[$index];
            }
            $params[] = $parameters;

            $oController = new $controllerClass();
            if (!method_exists($oController, $method)) {
                throw new Exception("method: {$method} does not exist in class: {$controllerClass}");
            } else {
                $reflectionMethod = new ReflectionMethod($controllerClass, $method);
                echo $reflectionMethod->invokeArgs($oController, $params);
                // call_user_func_array(array($oController, $method), $params);
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
        if ($this->hasEvent($route)) {
            return true;
        }

        $name = strtolower($route);
        $eventNames = array_keys($this->events);
        foreach ($eventNames as $eventName) {
            $pos = strpos($name, $eventName);
            if ($pos === false) {
                continue;
            }
            return true;
        }
        return false;
    }

    /**
     * Get route uri
     * route: 'test/:id?/:id2?/'
     * expected route uri => 'test/'
     *
     * @param string $routeString
     * @return string
     */
    protected function getRouteUri($routeString)
    {
        $pos = strpos($routeString, ':');
        return ($pos) ? substr($routeString, 0, $pos) : $routeString;
    }

    /**
     * Get parameter definition from route string
     * route: 'test/:id?/:id2?/'
     * expected variable definition => ':id?/:id2?/'
     *
     * @param string $routeString
     * @return string
     */
    protected function getParameterDefinitionFromRouteString($routeString)
    {
        if (substr($routeString, -1) === '/') {
            $routeString = substr($routeString, 0, -1);
        }
        $pos = strpos($routeString, ':');
        return ($pos) ? substr($routeString, $pos) : '';
    }

    /**
     * Emit event
     *
     * @param string $name 觸發事件
     * @param string $parameters 觸發事件時，外部傳入的參數
     * @return void
     */
    public function emit($name, $parameters)
    {
        $name = strtolower($name);
        $eventNames = array_keys($this->events);
        foreach ($eventNames as $eventName) {
            $pos = strpos($name, $eventName);
            if ($pos === false) {
                continue;
            }

            $foundEventName = substr($name, $pos, strlen($eventName));
            $routeParameterValueStr = substr($name, $pos + strlen($eventName));
            if (substr($routeParameterValueStr, -1) === '/') {
                $routeParameterValueStr = substr($routeParameterValueStr, 0, -1);
            }
            call_user_func($this->events[$foundEventName], $routeParameterValueStr, $parameters);
            return true;
        }
        return false;
    }
}
