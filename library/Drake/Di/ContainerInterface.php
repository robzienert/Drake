<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Di
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Container interface
 *
 * @category    Drake
 * @package     Drake_Di
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
interface Drake_Di_ContainerInterface
{
//    public function getObject($name, $args = array());
//    public function getSingletonObject($name, $args = array());

    public function load($filepath);

    public function setCache(Zend_Cache $cache);
    public function getCache();

    public function setCacheEnabled($flag = true);
    public function getCacheEnabled();

    public static function setDefaultCache(Zend_Cache $cache);
    public static function getDefaultCache();
}