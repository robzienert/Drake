<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Application
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Application plugin resource
 *
 * @category    Drake
 * @package     Drake_Application
 * @subpackage  Resources
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Application_Resource_View extends Drake_Application_Resource_ViewAbstract
{
    /**
     * Returns the Zend_View object
     * 
     * @return Zend_View
     */
    public function init()
    {
        parent::init();

        $view = $this->getView();
        $view->doctype('XHTML1_STRICT');
        $view->setEncoding('UTF-8');

        Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination-control.phtml');

        return $this->getView();
    }

    /**
     * Set the view object
     *
     * @return Drake_Application_Resource_View
     */
    public function setView($options = array())
    {
        $this->_view = new Zend_View($options);
        return $this;
    }
}