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
 * @package     Drake_Paginator
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */

/**
 * @namespace
 */
namespace Drake\Paginator\Adapter;

/**
 * A simple Doctrine2 Zend_Paginator Adapter.
 *
 * @category    Drake
 * @package     Drake_Paginator
 * @subpackage  Adapter
 * @copyright   Copyright (c) 2008-2010 Rob Zienert (http://robzienert.com)
 * @license     http://github.com/robzienert/Drake/blob/develop/LICENSE New BSD
 */
class Doctrine2 implements \Zend\Paginator\Adapter
{
    /**
     * @var int
     */
    protected $count;

    /**
     * @var string
     */
    protected $countColumn;

    /**
     * @var \Doctrine\ORM\QueryBuilder
     */
    protected $qb;

    /**
     * Construct
     *
     * @param \Doctrine\ORM\QueryBuilder $qb
     * @param <type> $countColumn
     */
    public function __construct(\Doctrine\ORM\QueryBuilder $qb, $countColumn = 'id')
    {
        $this->qb = $qb;
        $this->countColumn = $countColumn;
    }

    /**
     * Retrieves items based on the provided QueryBuilder
     *
     * @param integer $offset
     * @param integer $itemCountPerPage
     * @return <type>
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->qb->setFirstResult($offset);
        $this->qb->setMaxResults($itemCountPerPage);

        $result = $this->qb->getQuery()->getResult();
        return $result;
    }

    /**
     * Counts the number of records from the QueryBuilder
     *
     * @return integer
     */
    public function count()
    {
        if (null === $this->count) {
            $qb = clone $this->qb;
            $qb->add('select', $qb->expr()->count(
                    $qb->getRootAlias() . '.' . $this->countColumn));
            $qb->setMaxResults(null);
            $qb->setFirstResult(null);

            $result = $qb->getQuery()->getResult();
            if (!is_array($result) && !isset($result[0][1])) {
                $result = 0;
            } else {
                $result = (int) $result[0][1];
            }

            $this->count = $result;
        }

        return $this->count;
    }
}