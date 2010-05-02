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
 * Needs documentation
 *
 * @category    Drake
 * @package     Drake_Paginator
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Paginator extends Zend_Paginator
{
    /**
     * Current page lower bound
     *
     * @var integer
     */
    protected $_pageLowerBound;

    /**
     * Current page upper bound
     *
     * @var integer
     */
    protected $_pageUpperBound;

    /**
     * Page range for pagination controls; overridden from the default 10
     *
     * @var integer
     */
    protected $_pageRange = 5;

    /**
     * Returns the current page's lower boundry page number
     *
     * @return integer
     */
    public function getPageLowerBound()
    {
        if (null === $this->_pageLowerBound) {
            $page = $this->getCurrentPageNumber();
            if ($page == 1) {
                $this->_pageLowerBound = 1;
            } else {
                $this->_pageLowerBound = ($page - 1) * $this->getItemCountPerPage();
            }
        }

        return $this->_pageLowerBound;
    }

    /**
     * Returns the current page's upper boundry page number
     *
     * @return integer
     */
    public function getPageUpperBound()
    {
        if (null === $this->_pageUpperBound) {
            $upperBound = $this->getItemCountPerPage();
            if (1 < $this->getCurrentPageNumber()) {
                $upperBound += $this->getPageLowerBound();
            }

            $totalItems = $this->getTotalItemCount();

            if ($upperBound > $totalItems) {
                $upperBound = $totalItems;
            }

            $this->_pageUpperBound = $upperBound;
        }

        return $this->_pageUpperBound;
    }
}