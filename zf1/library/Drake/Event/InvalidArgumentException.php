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
 * InvalidArgumentException
 *
 * Based off the Zend_Message proposal:
 * http://zendframework.com/wiki/pages/viewpage.action?pageId=41398
 *
 * @category    Drake
 * @package     Drake_Event
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Event_InvalidArgumentException extends InvalidArgumentException
    implements Drake_Event_Exception
{
}