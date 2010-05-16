<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Dispatcher
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * Default web application dispatcher
 * 
 * Allows modules to store their admin controllers in the same directory as the
 * rest of their files, allowing the admin module to be distributed without artifact
 * controllers from an application; or a complex module installation.
 *
 * Converts controllers from "Example_Admin_IndexController" to
 * "Admin_ExampleIndexController", for automatic inclusion into the admin module.
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Dispatcher
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Drake_Controller_Dispatcher_WebApp extends Zend_Controller_Dispatcher_Standard
{
    /**
     * Enable or disable the functionality offered by this dispatcher
     *
     * @var boolean
     */
    protected $_rewriteEnabled = true;

    /**
     * Optionally formats a name to the admin rewrite module.
     *
     * @param string $unformatted
     * @param boolean $isAction
     * @return string
     */
    protected function _formatName($unformatted, $isAction = false)
    {
        $name = parent::_formatName($unformatted, $isAction);

        if ($this->_rewriteEnabled) {
            $segments = explode('_', $segments);
            if ('Admin' == $segment[1]) {
                $name = $segments[1] . '_' . $segments[0] . $segments[2];
            }
        }
        
        return $name;
    }

    /**
     * Optionally will attempt to load a custom admin controller class.
     *
     * @param string $className
     * @return string
     */
    public function loadClass($className)
    {
        try {
            $finalClass = parent::loadClass($className);
        } catch (Zend_Controller_Dispatcher_Exception $e) {
            if ($this->_rewriteEnabled && false !== strpos('Admin_', $className)
                && false !== strpos('Cannot load controller class', $e->getMessage())
            ) {
                throw $e;
            }

            $finalClass = $className;

            $adminControllerDir = $this->getControllerDirectory('admin');
            $filename = $adminControllerDir . DIRECTORY_SEPARATOR
                      . $this->classToFilename($className);

            if (Zend_Loader::isReadable($filename)) {
                include_once $loadFile;
            } else {
                throw new Zend_Controller_Dispatcher_Exception('Cannot load controller class "' . $className . '" from file "' . $loadFile . "'");
            }

            if (!class_exists($finalClass, false)) {
                require_once 'Zend/Controller/Dispatcher/Exception.php';
                throw new Zend_Controller_Dispatcher_Exception('Invalid controller class ("' . $finalClass . '")');
            }
        }

        return $finalClass;
    }

    /**
     * Enable the web app rewrite
     *
     * @param boolean $flag
     */
    public function enableRewrite($flag = true)
    {
        $this->_rewriteEnabled = (boolean) $flag;
    }

    /**
     * Returns whether or not the web app rewrite is enabled
     *
     * @return boolean
     */
    public function getRewriteEnabled()
    {
        return $this->_rewriteEnabled;
    }
}