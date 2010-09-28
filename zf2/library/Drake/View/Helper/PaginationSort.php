<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\View\Helper;

/**
 * Creates a pagination sorting link for use in data grids, etc.
 *
 * @category    Drake
 * @package     Drake_View
 * @subpackage  Helper
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class PaginationSort extends \Zend\View\Helper\AbstractHelper
{
    /**
     * Creates a pagination sorting link
     *
     * @param string $label
     * @param string $column
     * @param string $title
     * @return string
     */
    public function paginationSort($label, $column, $title = 'Click to reorder')
    {
        $front = \Zend\Controller\Front::getInstance();
        $request = $front->getRequest();

        $currentColumn = $request->getParam('sort', null);
        $direction = ($request->getParam('direction', 'asc'))
            ? 'desc'
            : 'asc';

        if ($currentColumn == $column) {
            $inverse = ($direction == 'desc') ? 'asc' : 'desc';
            
            $html = sprintf(
                '<a href="%s" title="%s">%s <span class="%s">%s</span></a>',
                $this->view->url(array('sort' => $column, 'direction' => $inverse)),
                $title,
                $label,
                $inverse,
                $inverse);
        } else {
            $html = sprintf(
                '<a href="%s" title="%s">%s</a>',
                $this->view->url(array('sort' => $column, 'direction' => $direction)),
                $title,
                $label);
        }

        return $html;
    }
}