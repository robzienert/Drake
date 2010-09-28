<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Trait
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * toJson object trait
 *
 * Originally developed by Steve Hollis (steve@hlmenterprises.co.uk).
 * 
 * @category    Drake
 * @package     Drake_Trait
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Trait_Json extends Drake_Trait_TraitAbstract
{
    /**
     * Turn an object into a json string
     *
     * @param object $object
     * @return string
     * @throws Drake_Trait_InvalidArgumentException When an object is not provided
     */
    public function toJson($object)
    {
        if (!is_object($object)) {
            throw new Drake_Trait_InvalidArgumentException("Object provided to trait was not an object!");
        }

        $json = json_encode(get_object_vars($object));

        return $json;
    }
}