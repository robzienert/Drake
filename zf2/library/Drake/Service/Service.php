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
 * @package     Drake_Service
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Service;

/**
 * A model that defines common functionality to access data in a consistent
 * manner, allowing data access to be abstracted away.
 *
 * Finder methods should start with 'find', 'findAll', 'findBy', 'findOne',
 * and so-on.
 *
 * @category    Drake
 * @package     Drake_Service
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
interface Service
{
    /**
     * Set the DAO object.
     *
     * @param <type> $dao
     * @return void
     */
    public function setDao($dao);

    /**
     * Get the DAO object
     *
     * @return <type> $dao
     */
    public function getDao();

    /**
     * Create a new entity
     *
     * @param <type> $entity
     * @return void
     */
    public function create($entity);

    /**
     * Update the provided entity
     *
     * @param <type> $entity
     * @return void
     */
    public function update($entity);

    /**
     * Delete the provided entity
     *
     * @param <type> $entity
     * @return void
     */
    public function delete($entity);

    /**
     * Commits all changes
     *
     * @return void
     */
    public function flush();
}