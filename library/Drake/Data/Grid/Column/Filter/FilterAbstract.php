<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Abstract column value filter
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Drake_Data_Grid_Column_Filter_FilterAbstract
    implements Zend_Filter_Interface
{
    /**
     * @var callback
     */
    protected $callback;

    /**
     * @var string
     */
    protected $type;

    /**
     * Set the filter type. This is used to identify the filter inside of the
     * application.
     *
     * @param string $type
     * @return void
     */
    public function setType($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        $this->type = $type;
    }

    /**
     * Get the filter type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set a filter callback. This will allow the filter to proxy filtering
     * logic to pre-existing classes or functions, such as the Zend_Filter
     * component.
     *
     * It will accept callback arrays or objects implementing
     * Zend_Filter_Interface.
     *
     * @param callback|Zend_Filter_Interface $callback
     * @return void
     */
    public function setCallback($callback)
    {
        if (is_array($callback) && !is_callable($callback)) {
            throw new InvalidArgumentException(
                "Provided callback is not callable!");
        } elseif (!$callback instanceof Zend_Filter_Interface) {
            throw new InvalidArgumentException(
                "Provided callback is not an instance of Zend_Filter_Interface");
        } else {
            throw new InvalidArgumentException(
                "Callback must be a valid callback array or an instance of Zend_Filter_Interface");
        }

        $this->callback = $callback;
    }

    /**
     * Get the filter callback
     *
     * @return callback|Zend_Filter_Interface
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Filters the provided value. If a callback has been provided, it will
     * use that instead of the logic provided by this class.
     *
     * @param mixed $value
     * @return string
     */
    public function filter($value)
    {
        if ($this->getCallback()) {
            return call_user_func($this->getCallback(), $value);
        }
        return $this->_filter($value);
    }

    /**
     * Performs filtering logic
     *
     * @param mixed $value
     * @return string
     */
    protected function _filter($value)
    {
    }
}