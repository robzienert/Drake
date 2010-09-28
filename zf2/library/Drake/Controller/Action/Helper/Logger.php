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
 * @namespace
 */
namespace Drake\Controller\Action\Helper;

use \Zend\Registry as Registry;

/**
 * Logger action helper
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Helpers
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Logger extends \Zend\Controller\Action\Helper\AbstractHelper
{
    /**
     * Logger instance
     *
     * @var Zend_Log
     */
    private $logger;

    /**
     * Constructor
     *
     */
    public function __construct()
    {
        if (Registry::isRegistered('logger')) {
            $this->logger = Registry::get('logger');
        } else {
            throw new RuntimeException(
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
        if ($this->logger) {
            $this->logger->log($message, $priority);
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
        if ($this->logger) {
            array_pad($args, 1);
            $this->logger->$method($args[0]);
        }
    }
}