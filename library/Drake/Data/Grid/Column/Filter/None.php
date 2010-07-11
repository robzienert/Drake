<?php
class Drake_Data_Grid_Column_Filter_None extends Drake_Data_Grid_Column_Filter_FilterAbstract
{
    /**
     * @var string
     */
    protected $_type = 'none';

    /**
     * This filter does nothing. Useful in conjunction with links and so-on.
     *
     * @param string $value
     * @return string
     */
    protected function _filter($value)
    {
        return $value;
    }
}