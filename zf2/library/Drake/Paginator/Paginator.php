<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Paginator
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\Paginator;

/**
 * Needs documentation
 *
 * @category    Drake
 * @package     Drake_Paginator
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Paginator extends \Zend\Paginator\Paginator
{
    /**
     * Current page lower bound
     *
     * @var integer
     */
    protected $pageLowerBound;

    /**
     * Current page upper bound
     *
     * @var integer
     */
    protected $pageUpperBound;

    /**
     * Page range for pagination controls; overridden from the default 10
     *
     * @var integer
     */
    protected $pageRange = 5;

    /**
     * Returns the current page's lower boundry page number
     *
     * @return integer
     */
    public function getPageLowerBound()
    {
        if (null === $this->pageLowerBound) {
            $page = $this->getCurrentPageNumber();
            if ($page == 1) {
                $this->pageLowerBound = 1;
            } else {
                $this->pageLowerBound = ($page - 1) * $this->getItemCountPerPage();
            }
        }

        return $this->pageLowerBound;
    }

    /**
     * Returns the current page's upper boundry page number
     *
     * @return integer
     */
    public function getPageUpperBound()
    {
        if (null === $this->pageUpperBound) {
            $upperBound = $this->getItemCountPerPage();
            if (1 < $this->getCurrentPageNumber()) {
                $upperBound += $this->getPageLowerBound();
            }

            $totalItems = $this->getTotalItemCount();

            if ($upperBound > $totalItems) {
                $upperBound = $totalItems;
            }

            $this->pageUpperBound = $upperBound;
        }

        return $this->pageUpperBound;
    }
}