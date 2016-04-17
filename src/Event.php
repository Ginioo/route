<?php
/**
 * 事件
 *
 * @author  Gino Wu
 * @since   2015.10.01
 * @version v1
 */
namespace Ginioo\Sandbox;

class Event
{
    /**
     * events list
     *
     * @var array
     */
    private $events;

    /**
     * constructor
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->events = array();
    }

    /**
     * To check if there is a event or not.
     *
     * @param string $name 事件名稱
     * @return boolean
     */
    public function hasEvent($name)
    {
        $name=strtolower($name);
        return isset($this->events[$name]);
    }

    /**
     * To check if there is a event or not.
     *
     * @param string $name 事件名稱
     * @param string $callback callback function.
     * @return void
     */
    public function on($name, $callback)
    {
        $name = strtolower($name);
        if (!isset($this->events[$name]) && is_callable($callback, true)) {
            $this->events[$name] = $callback;
        }
    }

    /**
     * To check if there is a event or not.
     *
     * @param string $name 事件名稱
     * @param string $parameters 觸發事件時，外部傳入的參數
     * @return void
     */
    public function emit($name, $parameters)
    {
        $name = strtolower($name);
        if (isset($this->events[$name])) {
            call_user_func($this->events[$name], $parameters);
        }
    }
}
