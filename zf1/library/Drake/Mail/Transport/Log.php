<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Mail
 * @subpackage  Transport
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Sends mail to a log instead of actually sending through a mail transport.
 *
 * Originally developed by Aaron van Kaam (http://twitter.com/rabbyte).
 *
 * @category    Drake
 * @package     Drake_Mail
 * @subpackage  Transport
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Mail_Transport_Log extends Zend_Mail_Transport_Abstract
{
    /**
     * @var Zend_Log_Writer_Abstract
     */
    protected $_logger;

    /**
     * Name of the logger
     *
     * @var string
     */
    protected $_loggerName;

    /**
     * Constructor
     *
     * @todo Allow options for affecting the Formatter
     *
     * @param array $options
     * @return void
     */
    public function __construct(array $options = array())
    {
        if (isset($options['logger'])) {
            $this->_logger = $options['logger'];
        } elseif (isset($options['logger_name'])) {
            $this->_loggerName = $options['logger_name'];
        }
    }

    /**
     * Return the log object
     *
     * @return Zend_Log_Writer_Abstract
     */
    public function getLogger()
    {
        if (null === $this->_logger) {
            if (null !== $this->_loggerName) {
                $this->_logger = Zend_Registry::get('log')->{$this->_loggerName};
            }
            if (!$this->_logger) {
                $this->_logger = new Zend_Log(new Zend_Log_Writer_Null());
            }
        }
        
        return $this->_logger;
    }

    /**
     * Send an the mail to the logger instead of firing off an actual email.
     *
     * @return void
     */
    protected function _sendMail()
    {
        $this->getLogger()->info(array(
            'timestamp' => date('c'),
            'message' => array(
                'headers' => $this->header,
                'body'    => $this->body,
            )));
    }
}