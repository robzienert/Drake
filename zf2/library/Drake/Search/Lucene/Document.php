<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Search_Lucene
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\Search\Lucene;

/**
 * Adds standardized functionality and auto-maintenance of documents.
 *
 * @category    Drake
 * @package     Drake_Search_Lucene
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Document extends \Zend\Search\Lucene\Document
{
    /**
     * Adds defualt fields to the document
     *
     * @param string $class
     * @param mixed $key
     */
    public function __construct($class, $key)
    {
        $this->addField(\Zend\Search\Lucene\Field::keyword('docRef', "{$class}:{$key}"));
        $this->addField(\Zend\Search\Lucene\Field::unIndexed('class', $class));
        $this->addField(\Zend\Search\Lucene\Field::unIndexed('key', $key));
    }
}