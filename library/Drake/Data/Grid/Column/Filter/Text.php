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
 * Basic text column filter
 *
 * @category    Drake
 * @package     Drake_Data
 * @subpackage  Grid_Column_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Data_Grid_Column_Filter_Text
    extends Drake_Data_Grid_Column_Filter_FilterAbstract
{
    /**
     * @var string
     */
    protected $_type = 'text';

    /**
     * Constructor
     *
     */
    public function  __construct()
    {
        $this->setCallback(new Zend_Filter_Alnum(true));
    }
}