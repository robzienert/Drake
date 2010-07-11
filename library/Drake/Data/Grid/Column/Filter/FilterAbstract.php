<?php
abstract class Drake_Data_Grid_Column_Filter_FilterAbstract
    implements Zend_Filter_Interface
{
    protected $callback;
    protected $type;

    public function setType($type)
    {
        $type = Drake_Util_StringInflector::underscore($type);
        $this->type = $type;
    }

    public function getType()
    {
        return $this->type;
    }

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

    public function getCallback()
    {
        return $this->callback;
    }

    public function filter($value)
    {
        if ($this->getCallback()) {
            return call_user_func($this->getCallback(), $value);
        }
        return $this->_filter($value);
    }

    abstract protected function _filter($value)
    {
    }
}