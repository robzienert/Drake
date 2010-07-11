<?php
class Drake_Data_Grid_Column_Filter_Text extends Drake_Data_Grid_Column_Filter_FilterAbstract
{
    protected $type = 'text';

    public function  __construct()
    {
        $this->setCallback(new Zend_Filter_Alnum(true));
    }
}