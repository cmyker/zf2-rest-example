<?php

namespace News\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Paginator\Paginator;
use Zend\Paginator\Adapter\DbSelect;

/**
 * Paginator factory
 *
 * Class PaginatorFactory
 * @package News\Model
 */
class PaginatorFactory
{

    /**
     * Create a new paginator
     *
     * @param TableGateway $tableGateway
     * @param Select|null $select
     * @return Paginator
     */
    public function create(TableGateway $tableGateway, array $where = null)
    {
        $select = $tableGateway->getSql()->select();
        if ($where) {
            $select->where($where);
        }
        $resultSetPrototype = $tableGateway->getResultSetPrototype();
        $paginatorAdapter = new DbSelect($select, $tableGateway->getAdapter(), $resultSetPrototype);
        $paginator = new Paginator($paginatorAdapter);
        $paginator->setItemCountPerPage(50);
        return $paginator;
    }

}
