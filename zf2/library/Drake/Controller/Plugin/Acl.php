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
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Controller\Plugin;

use \Drake\Controller\Request,
    \Zend\Acl as ZendAcl,
    \Zend\Auth as ZendAuth,
    \Zend\Controller\Front as ControllerFront;

/**
 * An ACL package taken heavily from the Xyster Framework by Jonathan Hawk.
 * Small changes made and actively maintained.
 * http://forge.libreworks.net/projects/xyster/
 *
 * @todo This component needs to be updated for ZF2.
 *
 * @category    Drake
 * @package     Drake_Controller
 * @subpackage  Plugins
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class Acl extends \Zend\Controller\Plugin\AbstractHelper
{
    /**
     * @var Zend_Acl
     */
    protected $acl;

    /**
     * Action to use for errors; defaults to 'error'
     * @var string
     */
    protected $deniedAction = 'error';

    /**
     * Controller to use for errors; defaults to 'error'
     * @var string
     */
    protected $deniedController = 'error';

    /**
     * Module to use for errors; defaults to default module in dispatcher
     * @var string
     */
    protected $deniedModule;

    /**
     * Action to use for login; defaults to 'index'
     * @var string
     */
    protected $loginAction = 'index';

    /**
     * Controller to use for login; defaults to 'login'
     * @var string
     */
    protected $loginController = 'login';

    /**
     * Module to use for login; defaults to default module in dispatcher
     * @var string
     */
    protected $loginModule;

    /**
     * Constructor
     *
     * @param Zend_Acl $acl
     */
    public function __construct(ZendAcl $acl)
    {
        $this->setAcl($acl);
    }

    /**
     * Set the ACL
     *
     * @return void
     */
    public function setAcl(ZendAcl $acl)
    {
        $this->acl = $acl;
    }

    /**
     * Called before an action is dispatched by Zend_Controller_Dispatcher.
     *
     * @param Zend_Controller_Request_Abstract $request
     * @return void
     */
    public function preDispatch(\Zend\Controller\Request\AbstractRequest $request)
    {
        if (!$this->acl) {
            return;
        }

        $resource = $this->getResource(
            $request->getModuleName(),
            $request->getControllerName());
        $privilege = $request->getActionName();

        $container = ControllerFront::getInstance()
            ->getParam('bootstrap')
            ->getContainer();

        $userService = $container->userService;
        $role = $userService->getActiveUser()->getRoleId();

        $isAllowed = $this->acl->isAllowed($role, $resource);

        if (!$isAllowed && !ZendAuth::getInstance()->hasIdentity()) {
            $this->acl->allow(null,
                $this->getResource(
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

            $error = new \ArrayObject(array(), \ArrayObject::ARRAY_AS_PROPS);
            $error->exception = new ZendAcl\AclException($message);
            $error->type = 'EXCEPTION_OTHER';

            $error->request = clone $request;

            $this->acl->allow(null,
                $this->getResource(
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
            $this->deniedModule = ControllerFront::getInstance()
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
            $this->loginModule = ControllerFront::getInstance()
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
            $moduleResource = new Request\Resource($module);
            if (!$this->acl->has($moduleResource)) {
                $this->acl->add($moduleResource);
            }
            $resource = $moduleResource;
        }
        if ($module && $controller) {
            $controllerResource = new Request\Resource(
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