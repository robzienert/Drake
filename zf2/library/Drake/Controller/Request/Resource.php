<?php
/**
 * Drake Framework
 *
 * This source file is subject to the new BSD license that is bundled with this
 * package in the file LICENSE.
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

/**
 * @namespace
 */
namespace Drake\Controller\Request;

/**
 * An ACL package taken heavily from the Xyster Framework by Jonathan Hawk.
 * Small changes made and actively maintained.
 *
 * http://forge.libreworks.net/projects/xyster/
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
class Resource implements \Zend\Acl\Resource
{
    /**
     * @var string
     */
    private $module;

    /**
     * @var string
     */
    private $controller;

    /**
     * Constructor
     *
     * @param string $module
     * @param string $controller
     */
    public function __construct($module, $controller = null)
    {
        $this->module = $module;
        $this->controller = $controller;
    }

    /**
     * Get module
     *
     * @return string
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Get controller
     *
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * Get the string identifier of the Resource
     *
     * @return string
     */
    public function getResourceId()
    {
        $resource = 'mvc:' . $this->module;
        if ($this->controller) {
            $resource .= '.' . $this->controller;
        }
        return $resource;
    }

    /**
     * Get the string identifier of the Resource
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getResourceId();
    }
}