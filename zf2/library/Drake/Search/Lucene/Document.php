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
 * @package     Drake_Search_Lucene
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Search\Lucene;

use \Zend\Search\Lucene\Field;

/**
 * Adds standardized functionality and auto-maintenance of documents.
 * 
 * @category    Drake
 * @package     Drake_Search_Lucene
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
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
        $this->addField(Field::keyword('docRef', "{$class}:{$key}"));
        $this->addField(Field::unIndexed('class', $class));
        $this->addField(Field::unIndexed('key', $key));
    }
}