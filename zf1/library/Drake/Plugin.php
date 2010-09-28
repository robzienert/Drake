<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Plugin
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Base plugin abstract
 *
 * @category    Drake
 * @package     Drake_Plugin
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Drake_Plugin
{
    /**
     * Event dispatcher
     *
     * @var Drake_Event_Dispatcher
     */
    protected $_dispatcher;

    /**
     * Default event dispatcher
     *
     * @var Drake_Event_Dispatcher
     */
    protected static $_defaultDispatcher;

    /**
     * Constructor
     *
     * Optionally assign an event dispatcher and initialize the plugin
     *
     * @param array $options Plugin options
     */
    public function __construct(array $options = array())
    {
        if (isset($options['dispatcher']) && ($options['dispatcher'] instanceof Drake_Event_Dispatcher)) {
            $this->setDispatcher($options['dispatcher']);
        } else {
            $this->setDispatcher(self::getDefaultDispatcher());
        }
        $this->_init();
    }

    /**
     * Init hook
     *
     */
    abstract protected function _init();

    /**
     * Set the dispatcher
     *
     * @param Drake_Event_Dispatcher $dispatcher
     * @return Drake_Plugin
     */
    public function setDispatcher(Drake_Event_Dispatcher $dispatcher)
    {
        $this->_dispatcher = $dispatcher;
        return $this;
    }

    /**
     * Get the dispatcher
     *
     * @return Drake_Event_Dispatcher
     */
    public function getDispatcher()
    {
        return $this->_dispatcher;
    }

    /**
     * Subscribe to an event
     *
     * @param string $event
     * @param string $method
     * @return Drake_Plugin
     */
    public function subscribe($event, $method)
    {
        $this->getDispatcher()->subscribe($event, array($this, $method));
        return $this;
    }

    /**
     * Set the default event dispatcher
     *
     * @param Drake_Event_Dispatcher $dispatcher
     */
    public static function setDefaultDispatcher(Drake_Event_Dispatcher $dispatcher)
    {
        self::$_defaultDispatcher = $dispatcher;
    }

    /**
     * Get the default event dispatcher
     *
     * @return Drake_Event_Dispatcher
     */
    public static function getDefaultDispatcher()
    {
        return self::$_defaultDispatcher;
    }
}