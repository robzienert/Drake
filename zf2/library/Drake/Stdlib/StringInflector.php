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
class StringInflector
{
    /**
     * Convert a string to CamelCase.
     *
     * @param string $string
     * @return string
     */
    public static function camelize($string)
    {
        $string = preg_replace('/[^A-Z^a-z^0-9]+/', ' ', $string);
        $string = ucwords($string);
        $string = str_replace(' ', '', $string);
        return $string;
    }

    /**
     * Creates an underscored and lowercase string.
     *
     * @param string $string
     * @return string
     */
    public static function underscore($string)
    {
        return strtolower(preg_replace('/(?!^)[[:upper:]]/', '_' . '\0', $string));
    }

    /**
     * Converts a string to a URL slug
     *
     * @param string $string
     * @return string
     */
    public static function sluggify($string)
    {
        $string = iconv('utf-8', 'ascii//TRANSLIT', $string);
        $string = strtolower($string);
        $string = preg_replace('/[^a-z0-9\-_]+/', '-', $string);
        $string = preg_replace('/-{2,}/', '-', $string);
        return $string;
    }
}