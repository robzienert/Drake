<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Translator
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake;

/**
 * Extended Translate class for variable insertion
 *
 * @category    Drake
 * @package     Drake_Translator
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Translator extends \Zend\Translator
{
    /**
     * Adds sprintf support to translation strings
     *
     * @todo This should be refactored to look for replacement keys instead.
     *
     * @return string
     */
    public function __()
    {
        $args = func_get_args();
        $num = func_num_args();

        $adapter = $this->getAdapter();
        $args[0] = $adapter->_($args[0]);

        if ($num <= 1) {
            return $args[0];
        }

        return call_user_func_array('sprintf', $args);
    }
}