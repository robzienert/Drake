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
 * @package     Drake_Translator
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake;

/**
 * @category    Drake
 * @package     Drake_Translator
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class Translator extends \Zend\Translator\Translator
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