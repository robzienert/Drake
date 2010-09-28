<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Event
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Event dispatcher
 *
 * Based off the Zend_Message proposal:
 * http://zendframework.com/wiki/pages/viewpage.action?pageId=41398
 *
 * @category    Drake
 * @package     Drake_Event
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Event_Dispatcher
{
    /**
     * Event listeners
     *
     * @var array
     */
    protected $_listeners = array();

    /**
     * Subscribe to an event
     *
     * @param string $name Name of the event
     * @param callback $callback Callback for the event
     */
    public function subscribe($name, $callback)
    {
        if (!is_callable($callback)) {
            throw new Drake_Event_InvalidArgumentException('You need to provide a valid callback.');
        }
        $this->_listeners[$name][] = $callback;
    }

    /**
     * Unsubscribe from an event
     *
     * @param string $name Name of the event
     * @param callback $callback Callback for the event
     * @return void
     */
    public function unsubscribe($name, $callback)
    {
        if (!empty($this->_listeners[$name])) {
            foreach ($this->_listeners[$name] as $key => $listener) {
                if ($callback == $listener) {
                    unset($this->_listeners[$name][$key]);
                    return;
                }
            }
        }
    }

    /**
     * Dispatch an event
     *
     * @param Drake_Event $event
     */
    public function dispatch(Drake_Event $event)
    {
        $name = $event->getName();
        if (!empty($this->_listeners[$name])) {
            foreach ($this->_listeners[$name] as $listener) {
                call_user_func($listener, $event);
            }
        }
    }
}