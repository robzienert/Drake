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
 * The base event message object
 *
 * @category    Drake
 * @package     Drake_Event
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Event implements ArrayAccess
{
    /**
     * The event's name
     *
     * @var string
     */
    protected $_name;

    /**
     * The event's subject
     *
     * @var mixed
     */
    protected $_subject;

    /**
     * Event parameters
     *
     * @var array
     */
    protected $_parameters = array();

    /**
     * Constructor
     *
     * @param mixed $subject
     * @param string $name
     * @param array $parameters
     */
    public function __construct($subject, $name, array $parameters = array())
    {
        $this->_subject = $subject;
        $this->_name = $name;
        $this->_parameters = $parameters;
    }

    /**
     * Get the event's subject
     *
     * @return mixed
     */
    public function getSubject()
    {
        return $this->_subject;
    }

    /**
     * Get the event's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->_name;
    }

    /**
     * Check if the offset exists
     *
     * @param mixed $offset
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->_parameters[$offset]);
    }

    /**
     * Get a parameter
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->_parameters[$offset];
    }

    /**
     * Set a parameter
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->_parameters[$offset] = $value;
    }

    /**
     * Unset a parameter
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->_parameters[$offset]);
    }
}