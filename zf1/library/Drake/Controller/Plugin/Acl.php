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
class Drake_Controller_Plugin_Acl extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Acl
     */
    protected $acl;

    /**
     * Action to use for errors; defaults to 'error'
     * @var string
     */
    protected $_deniedAction = 'error';

    /**
     * Controller to use for errors; defaults to 'error'
     * @var string
     */
    protected $_deniedController = 'error';

    /**
     * Module to use for errors; defaults to default module in dispatcher
     * @var string
     */
    protected $_deniedModule;

    /**
     * Action to use for login; defaults to 'index'
     * @var string
     */
    protected $_loginAction = 'index';

    /**
     * Controller to use for login; defaults to 'login'
     * @var string
     */
    protected $_loginController = 'login';

    /**
     * Module to use for login; defaults to default module in dispatcher
     * @var string
     */
    protected $_loginModule;

    /**
     * Constructor
     *
     * @param Zend_Acl $acl
     */
    public function __construct(Zend_Acl $acl)
    {
        $this->setAcl($acl);
    }

    /**
     * Set the ACL
     *
     * @return void
     */
    public function setAcl(Zend_Acl $acl)
    {
        $this->acl = $acl;
    }

    /**
     * Called before an action is dispatched by Zend_Controller_Dispatcher.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(Zend_Controller_Request_Abstract $request)
    {
        if (!$this->acl) {
            return;
        }

        $resource = $this->_getResource(
            $request->getModuleName(),
            $request->getControllerName());
        $privilege = $request->getActionName();

        $container = Zend_Controller_Front::getInstance()
            ->getParam('bootstrap')
            ->getContainer();

        $userService = $container->userService;
        $role = $userService->getActiveUser()->getRoleId();

        $isAllowed = $this->acl->isAllowed($role, $resource);

        if (!$isAllowed && !Zend_Auth::getInstance()->hasIdentity()) {
            $this->acl->allow(null,
                $this->_getResource(
                    $this->getLoginModule(),
                    $this->getLoginController()),
                $this->getLoginAction());
            $request->setModuleName($this->getLoginModule())
                ->setControllerName($this->getLoginController())
                ->setActionName($this->getLoginAction())
                ->setDispatched(false);
            return;
        }

        if (!$isAllowed) {
            $message = 'Insufficient permissions: ';
            $message .= $role . ' -> ' . $resource->getResourceId();

            $error = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
            $error->exception = new Zend_Acl_Exception($message);
            $error->type = 'EXCEPTION_OTHER';

            $error->request = clone $request;

            $this->acl->allow(null,
                $this->_getResource(
                    $this->getAccessDeniedModule(),
                    $this->getAccessDeniedController()),
                $this->getAccessDeniedAction());

            $request
                ->setParam('error_handler', $error)
                ->setModuleName($this->getAccessDeniedModule())
                ->setControllerName($this->getAccessDeniedController())
                ->setActionName($this->getAccessDeniedAction())
                ->setDispatched(false);
        }
    }

    /**
     * Setup the dispatch location for access denied errors
     *
     * @param string $module
     * @param string $controller
     * @param string $action
     */
    public function setAccessDenied( $module, $controller, $action )
    {
        $this->deniedModule = (string) $module;
        $this->deniedController = (string) $controller;
        $this->deniedAction = (string) $action;
    }

    /**
     * Setup the dispatch location for unauthenticated users
     *
     * @param string $module
     * @param string $controller
     * @param string $action
     */
    public function setLogin( $module, $controller, $action )
    {
        $this->loginModule = (string) $module;
        $this->loginController = (string) $controller;
        $this->loginAction = (string) $action;
    }

    /**
     * Retrieve the current acl plugin module
     *
     * @return string
     */
    public function getAccessDeniedModule()
    {
        if (null === $this->deniedModule) {
            $this->deniedModule = Zend_Controller_Front::getInstance()
                ->getDispatcher()
                ->getDefaultModule();
        }
        return $this->deniedModule;
    }

    /**
     * Retrieve the current acl plugin controller
     *
     * @return string
     */
    public function getAccessDeniedController()
    {
        return $this->deniedController;
    }

    /**
     * Retrieve the current acl plugin action
     *
     * @return string
     */
    public function getAccessDeniedAction()
    {
        return $this->deniedAction;
    }

    /**
     * Retrieve the current not-authenticated module
     *
     * @return string
     */
    public function getLoginModule()
    {
        if (null === $this->loginModule) {
            $this->loginModule = Zend_Controller_Front::getInstance()
                ->getDispatcher()
                ->getDefaultModule();
        }
        return $this->loginModule;
    }

    /**
     * Retrieve the current not-authenticated controller
     *
     * @return string
     */
    public function getLoginController()
    {
        return $this->loginController;
    }

    /**
     * Retrieve the current not-authenticated action
     *
     * @return string
     */
    public function getLoginAction()
    {
        return $this->loginAction;
    }

    /**
     * Get the resource object
     *
     * @param string $module
     * @param string $controller
     */
    protected function _getResource($module, $controller)
    {
        $resource = null;

        if ($module) {
            $moduleResource = new Drake_Controller_Request_Resource($module);
            if (!$this->acl->has($moduleResource)) {
                $this->acl->add($moduleResource);
            }
            $resource = $moduleResource;
        }
        if ($module && $controller) {
            $controllerResource = new Drake_Controller_Request_Resource(
                $module,
                $controller);
            if (!$this->acl->has($controllerResource)) {
                $this->acl->add($controllerResource);
            }
            $resource = $controllerResource;
        }
        return $resource;
    }
}