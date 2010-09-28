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
 * Adds standardized functionality and auto-maintenance of documents.
 *
 * @category    Drake
 * @package     Drake_Search_Lucene
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Search_Lucene extends Zend_Search_Lucene
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
        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
            new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive());
    }

    /**
     * Create a new Lucene index
     *
     * @param string $directory
     * @return Zend_Search_Lucene_Proxy
     */
    public static function create($directory)
    {
        return new Zend_Search_Lucene_Proxy(new Drake_Search_Lucene($directory, true));
    }

    /**
     * Open a new Lucene index
     *
     * @param string $directory
     * @return Zend_Search_Lucene_Proxy
     */
    public static function open($directory)
    {
        return new Zend_Search_Lucene_Proxy(new Drake_Search_Lucene($directory, false));
    }

    /**
     * Automatically removes matching documents from the index when adding a document. This allows
     * the index to self-manage it's documents without explicitly calling to find existing documents
     * that would conflict with an updated document.
     *
     * @param Zend_Search_Lucene_Document $document
     */
    public function addDocument(Zend_Search_Lucene_Document $document)
    {
        $docRef = $document->docRef;
        $term  = new Zend_Search_Lucene_Index_Term($docRef, 'docRef');

        $docIds = $this->termDocs($term);
        foreach ($docIds as $id) {
            $this->delete($id);
        }

        parent::addDocument($document);
    }
}