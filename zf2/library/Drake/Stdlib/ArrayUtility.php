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
 * @package     Drake_Stdlib
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Stdlib;

/**
 * @category    Drake
 * @package     Drake_Stdlib
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class ArrayUtility
{
    /**
     * Recursively convert data to an array
     *
     * @param mixed $data
     * @return array
     */
    public static function convertRecursive($data)
    {
        $array = array();
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $value = self::convertRecursive($value);
            }
            $array[$key] = $value;
        }
        return $array;
    }
}