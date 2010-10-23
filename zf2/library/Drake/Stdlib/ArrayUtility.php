<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Util
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\Stdlib;

/**
 * Needs documentation
 *
 * @category    Drake
 * @package     Drake_Util
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
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