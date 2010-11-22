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
 * @package     Drake_Search_Engine
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Search\Engine;

use Drake\Search\Lucene\Lucene;

/**
 * Handles the creation and maintenance of Lucene indicies.
 *
 * @category    Drake
 * @package     Drake_Search_Engine
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class Engine
{
    const INDEX_TYPE_STABLE = 'stable';
    const INDEX_TYPE_VOLATILE = 'volatile';

    /**
     * @var string Base path to the index directory.
     */
    protected $indexBasePath;

    /**
     * @var array Lucene objects
     */
    protected $indicies = array();

    /**
     * Constructor
     *
     * @param string $indexBasePath
     */
    public function  __construct($indexBasePath)
    {
        $this->indexBasePath = $indexBasePath;
    }

    /**
     * Get the specified index (stable or volatile).
     *
     * @param bool $stable
     * @return Drake\Search\Lucene\Lucene
     */
    public function getIndex($stable = true)
    {
        return ($stable)
            ? $this->initIndex($this->getStableIndexPath())
            : $this->initIndex($this->getVolatileIndexPath());
    }

    /**
     * Initializes an index, based on the directory passed.
     *
     * @param string $directory
     * @return Drake\Search\Lucene\Lucene
     */
    protected function initIndex($directory)
    {
        if (!array_key_exists($directory, $this->indicies)) {
            try {
                $this->indicies[$directory] = new Lucene($directory, false);
            } catch (Zend\Search\Lucene\LuceneException $e) {
                $this->indicies[$directory] = new Lucene($directory, true);
            }
        }
        
        return $this->indicies[$directory];
    }

    /**
     * Rebuilds a search index, given an array of objects that implement the
     * Searchable interface.
     *
     * @param array $searchables
     * @return void
     */
    public function build(array $searchables)
    {
        set_time_limit(0);

        $volatileIndex = $this->getIndex(false);

        foreach ($searchables as $searchable) {
            if (!$searchable instanceof Searchable) {
                throw new EngineException(sprintf(
                    '%s is not an instance of \Drake\Search\Engine\Searchable',
                    get_class($searchable)));
            }

            $documents = $searchable->getSearchDocuments();

            foreach ($documents as $document) {
                $volatileIndex->addDocument($document);
            }
        }

        $volatile->commit();
        $volatile->optimize();
        $this->replaceIndicies();
    }

    /**
     * Swaps the contents of the stable index with the contents of the volatile
     * index, then resets the volatile index to blank.
     *
     * @return void
     */
    protected function replaceIndicies()
    {
        $stableIndexPath = $this->getStableIndexPath();
        $volatileIndexPath = $this->getVolatileIndexPath();
        
        if (is_dir($stableIndexPath)) {
            $this->clearIndex();
            rmdir($stableIndexPath);
        }

        rename($volatileIndexPath, $stableIndexPath);

        mkdir($volatileIndexPath, 0755, true);
        chmod($volatileIndexPath, 0755);
    }

    /**
     * Removes the contents of an index.
     *
     * @param bool $stable
     * @return void
     */
    protected function clearIndex($stable = true)
    {
        $directory = ($stable)
            ? $this->getStableIndexPath()
            : $this->getVolatileIndexPath();

        if (is_dir($directory)) {
            $it = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory),
                \RecursiveIteratorIterator::CHILD_FIRST);
            
            foreach ($it as $resource) {
                if ($resource->isFile()) {
                    unlink($resource->getPathname());
                } else if ($file->isDir()) {
                    rmdir($resource->getPathname());
                }
            }
        }
    }

    /**
     * Retrieves the stable index path
     *
     * @return string
     */
    protected function getStableIndexPath()
    {
        return implode(DIRECTORY_SEPARATOR, array(
            $this->indexBasePath,
            self::INDEX_TYPE_STABLE
        ));
    }

    /**
     * Retrieves the volatile index path
     *
     * @return string
     */
    protected function getVolatileIndexPath()
    {
        return implode(DIRECTORY_SEPARATOR, array(
            $this->indexBasePath,
            self::INDEX_TYPE_VOLATILE
        ));
    }
}