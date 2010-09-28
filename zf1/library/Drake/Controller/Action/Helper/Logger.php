<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Controller
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Logger action helper
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Helpers
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Controller_Action_Helper_Logger extends Zend_Controller_Action_Helper_Abstract
{
    /**
     * Logger instance
     *
     * @var Zend_Log
     */
    private $_logger;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        if (Zend_Registry::isRegistered('logger')) {
            $this->_logger = Zend_Registry::get('logger');
        } else {
            throw new Drake_Controller_Action_Helper_RuntimeException(
                'Logger is not registered in the Registry');
        }
    }

    /**
     * Log a message
     *
     * @param string $message
     * @param int $priority
     */
    public function direct($message, $priority)
    {
        if ($this->_logger) {
            $this->_logger->log($message, $priority);
        }
    }

    /**
     * Magic call method
     *
     * @param string $method
     * @param array $args
     */
    public function __call($method, array $args = array())
    {
        if ($this->_logger) {
            array_pad($args, 1);
            $this->_logger->$method($args[0]);
        }
    }
}