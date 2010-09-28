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
        \Zend\Search\Lucene\Analysis\Analyzer::setDefault(
            new \Zend\Search\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive());
    }

    /**
     * Create a new Lucene index
     *
     * @param string $directory
     * @return Zend_Search_Lucene_Proxy
     */
    public static function create($directory)
    {
        return new \Zend\Search\Lucene\Proxy(new Lucene($directory, true));
    }

    /**
     * Open a new Lucene index
     *
     * @param string $directory
     * @return Zend_Search_Lucene_Proxy
     */
    public static function open($directory)
    {
        return new \Zend\Search\Lucene\Proxy(new Lucene($directory, false));
    }

    /**
     * Automatically removes matching documents from the index when adding a document. This allows
     * the index to self-manage it's documents without explicitly calling to find existing documents
     * that would conflict with an updated document.
     *
     * @param Zend_Search_Lucene_Document $document
     */
    public function addDocument(\Zend\Search\Lucene\Document $document)
    {
        $docRef = $document->docRef;
        $term  = new \Zend\Search\Lucene\Index\Term($docRef, 'docRef');

        $docIds = $this->termDocs($term);
        foreach ($docIds as $id) {
            $this->delete($id);
        }

        parent::addDocument($document);
    }
}