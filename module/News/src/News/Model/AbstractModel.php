<?php

namespace News\Model;

use Zend\Db\TableGateway\TableGateway;
use News\Exception\RestException;

class AbstractModel  implements ModelInterface
{

    /**
     * to override, used to get params from routeMatch and for exceptions
     */
    const NAME = null;

    /**
     * @var TableGateway
     */
    protected $tableGateway;

    /**
     * @var PaginatorFactory
     */
    protected $paginatorFactory;

    /**
     * @return PaginatorFactory
     */
    public function getPaginatorFactory()
    {
        return $this->paginatorFactory;
    }

    /**
     * @param PaginatorFactory $paginatorFactory
     */
    public function setPaginatorFactory($paginatorFactory)
    {
        $this->paginatorFactory = $paginatorFactory;
    }

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * @inheritdoc
     */
    public function create($data)
    {
        $this->tableGateway->insert($data);
        return $this->tableGateway->getLastInsertValue();
    }

    /**
     * @inheritdoc
     */
    public function delete($id)
    {
        return $this->tableGateway->delete(['id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        $rows = $this->tableGateway->select(['id' => $id]);
        $row = $rows->current();
        if (!$row) {
            throw new RestException("Could not find ".static::NAME." id $id");
        }
        return $row;
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        return $this->tableGateway->select();
    }

}
