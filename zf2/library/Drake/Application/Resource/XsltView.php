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
 * XSLT View resource bootstrapper
 *
 * @category    Drake
 * @package     Drake_Application
 * @subpackage  Resources
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Application_Resource_XsltView extends Drake_Application_Resource_ViewAbstract
{
    /**
     * Sets up XSLT-specific view settings
     *
     * @return Drake_View_Xslt
     */
    public function init()
    {
        parent::init();

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setViewSuffix('xsl');
        
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('_pagination-control.xsl');

        return $this->getView();
    }

    /**
     * Set the view object
     *
     * @param array $options
     * @return Drake_Application_Resource_XsltView
     */
    public function setView($options = array())
    {
        $this->_view = new Drake_View_Xslt($options);
        return $this;
    }

    /**
     * Get the view object
     *
     * @return Drake_View_Xslt
     */
    public function getView()
    {
        if (null === $this->_view) {
            $this->_view = new Drake_View_Xslt();
        }
        return $this->_view;
    }
}