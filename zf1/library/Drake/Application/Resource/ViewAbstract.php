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
 * Abstract resource for view bootstrapping
 *
 * @category    Drake
 * @package     Drake_Application
 * @subpackage  Resources
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
abstract class Drake_Application_Resource_ViewAbstract extends Zend_Application_Resource_ResourceAbstract
{
    /**
     * Application view object
     *
     * @var Zend_View
     */
    protected $_view;

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

        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        $view->addHelperPath('Drake/View/Helper', 'Drake_View_Helper');

        if (array_key_exists('helperpath', $options)) {
            foreach ($options['helperpath'] as $prefix => $path) {
                $view->addHelperPath($path, $prefix);
            }
        }

        return $this->_view;
    }

    abstract public function setView($options = array());

    public function getView()
    {
        return $this->_view;
    }
}