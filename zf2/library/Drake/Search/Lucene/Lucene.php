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

use \Zend\Search\Lucene\Analysis\Analyzer,
    \Zend\Search\Lucene\Document as ZendDocument,
    \Zend\Search\Lucene as ZendLucene,
    \Zend\Search\Lucene\Index\Term,
    \Zend\Search\Lucene\Proxy;

/**
 * Adds standardized functionality and auto-maintenance of documents.
 *
 * @category    Drake
 * @package     Drake_Search_Lucene
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class Lucene extends \Zend\Search\Lucene
{
    /**
     * Constructor; sets default analyzer
     *
     * @param string $directory
     * @param boolean $create
     */
    public function __construct($directory = null, $create = false)
    {
        parent::__construct($directory, $create);
        Analyzer::setDefault(new Analyzer\Common\Utf8Num\CaseInsensitive());
    }

    /**
     * Create a new Lucene index
     *
     * @param string $directory
     * @return Zend_Search_Lucene_Proxy
     */
    public static function create($directory)
    {
        return new Proxy(new Lucene($directory, true));
    }

    /**
     * Open a new Lucene index
     *
     * @param string $directory
     * @return Zend_Search_Lucene_Proxy
     */
    public static function open($directory)
    {
        return new Proxy(new Lucene($directory, false));
    }

    /**
     * Automatically removes matching documents from the index when adding a document. This allows
     * the index to self-manage it's documents without explicitly calling to find existing documents
     * that would conflict with an updated document.
     *
     * @param Zend_Search_Lucene_Document $document
     */
    public function addDocument(ZendDocument $document)
    {
        $docRef = $document->docRef;
        $term  = new Term($docRef, 'docRef');

        $docIds = $this->termDocs($term);
        foreach ($docIds as $id) {
            $this->delete($id);
        }

        parent::addDocument($document);
    }
}