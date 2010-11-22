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
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Filter;

/**
 * @category    Drake
 * @package     Drake_Filter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
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
        $value = \Drake\Stdlib\StringInflector::sluggify($value);
        return $value;
    }
}