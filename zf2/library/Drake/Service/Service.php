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
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD License
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
 */
interface Service
{
    public function setDao(Drake\Dao\Dao $dao);

    public function getDao();

    public function create($entity);

    public function update($entity);

    public function delete($entity);

    public function flush();
}