<?php
/**
 * Drake Framework
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the BSD License that is bundled with this
 * package in the file LICENSE. It is also available through the world-wide-web
 * at this URL: http://github.com/robzienert/Drake/blob/develop/LICENSE
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Helpers
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Controller\Action\Helper;

use \Zend\Registry as Registry;

/**
 * @uses        \Zend\Registry
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Helpers
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
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