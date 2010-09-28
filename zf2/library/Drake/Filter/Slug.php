<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\Filter;

/**
 * Slug filter
 *
 * @category    Drake
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Slug extends \Zend\Filter\AbstractFilter
{
    /**
     * Converts a string to a url-safe slug
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        $value = \Drake\Util\StringInflector::sluggify($value);
        return $value;
    }
}