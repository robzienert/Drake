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
 * @package     Drake_Application
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Application\Resource;

/**
 * @category    Drake
 * @package     Drake_Application
 * @subpackage  Resources
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class View extends ViewAbstract
{
    /**
     * Returns the Zend_View object
     * 
     * @return Zend_View
     */
    public function init()
    {
        $options = $this->getOptions();
        $title = 'New Drake Application';
        if (array_key_exists('title', $options)) {
            $title = $options['title'];
            unset($options['title']);
        }

        $this->setView($options);
        $view = $this->getView();

        $view->siteName = $title;

        $view->headTitle()->setSeparator(' - ')->append($title);
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');

        $viewRenderer = \Zend\Controller\Action\HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        $view->addHelperPath('Drake/View/Helper', 'Drake_View_Helper');

        if (array_key_exists('helperpath', $options)) {
            foreach ($options['helperpath'] as $prefix => $path) {
                $view->addHelperPath($path, $prefix);
            }
        }

        $view = $this->getView();
        $view->doctype('XHTML1_STRICT');
        $view->setEncoding('UTF-8');

        \Zend\View\Helper\PaginationControl::setDefaultViewPartial('_pagination-control.phtml');

        return $this->getView();
    }

    /**
     * Set the view object
     *
     * @return Drake_Application_Resource_View
     */
    public function setView($options = array())
    {
        $this->view = new \Zend\View($options);
        return $this;
    }
    
    /**
     * Get the view object
     *
     * @return Zend\View
     */
    public function getView()
    {
        return $this->view;
    }
}