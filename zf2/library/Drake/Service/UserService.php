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
 * @package     Drake_
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Service;

/**
 * Used by ACL components
 *
 * @category    Drake
 * @package     Drake_Service
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
interface UserService extends Service
{
    /**
     * Returns the currently active user for the request.
     *
     * This method must always return an object; such as an AnonymousUser object
     * that implements \Zend\Acl\Role.
     *
     * @return \Zend\Acl\Role
     */
    public function getActiveUser();
}