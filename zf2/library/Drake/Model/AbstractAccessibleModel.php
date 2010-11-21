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
 * @package     Drake_Model
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Model;

use Zend\Acl\Acl;

/**
 * @category    Drake
 * @package     Drake_Model
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
abstract class AbstractAccessibleModel extends AbstractModel implements \Zend\Acl\Resource
{
    /**
     * @var Zend_Acl
     */
    protected $acl;

    /**
     * @var Zend_Acl
     */
    protected static $defaultAcl;

    /**
     * @var string The ACL resource name
     */
    protected $resourceId;

    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Intiailize method
     *
     */
    protected function init()
    {
    }

    /**
     * Set and initialize the ACL for this model
     *
     * @param Zend_Acl $acl
     * @return void
     */
    public function setAcl(Acl $acl)
    {
        $this->acl = $acl;
        if (!$this->acl->hasResource($this)) {
            $this->acl->add($acl);
        }
        $this->initAcl();
    }

    /**
     * Get the ACL for this model; if none is set, attempt to use the default
     * ACL. If no default ACL is set, this will throw an exception.
     *
     * @return Zend_Acl
     * @throws LogicException If no ACL or default ACL has been defined
     */
    public function getAcl()
    {
        if (null === $this->acl) {
            if (null === ($acl = self::getDefaultAcl())) {
                throw new ModelException("No ACL or default ACL defined!");
            }
            $this->setAcl($acl);
        }
        return $this->acl;
    }

    /**
     * Initialize the ACL for this model.
     *
     * Use this method to add role privileges and assertions.
     *
     * @return void
     */
    protected function initAcl()
    {
    }

    /**
     * Set the model ACL resource id.
     *
     * @param string $resourceId
     * @return void
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }

    /**
     * Get the ACL resource id
     *
     * If one has not been set, it will auto-create one by converting from the
     * class name, for example:
     *
     * - "Users_Model_User" becomes "model:users.user"
     * - "Users_Model_UserProfile" becomes "model:users.userprofile"
     *
     * @return string
     */
    public function getResourceId()
    {
        if (null === $this->resourceId) {
            $filter = new \Zend\Filter\Work\SeparatorToSeparator('_', '.');
            $className = strtolower(get_class($this));
            $resourceId = 'model:' . $filter->filter(
                str_replace('_model', '', $className));
            
            $this->setResourceId($resourceId);
        }

        return $this->resourceId;
    }

    /**
     * Proxy to {@see Zend_Acl}. Simplies the API to automatically include the
     * model as the resource.
     *
     * @param Zend_Acl_Role_Interface|string $role
     * @param string $privilege
     * @return boolean
     */
    public function isAllowed($role, $privilege)
    {
        return $this->getAcl()->isAllowed($role, $this, $privilege);
    }

    /**
     * Set the default ACL object
     *
     * @param Zend_Acl $acl
     */
    public static function setDefaultAcl(Acl $acl)
    {
        self::$defaultAcl = $acl;
    }

    /**
     * Get the default ACL object
     *
     * @return Zend_ACl|null
     */
    public static function getDefaultAcl()
    {
        return self::$defaultAcl;
    }
}