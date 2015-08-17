<?php
namespace News\Model;


interface ModelInterface
{

    /**
     * Get an item from DB
     *
     * @param int $id
     * @return mixed
     */
    public function get($id);

    /**
     * Get an all items from DB
     *
     * @return mixed
     */
    public function getAll();

    /**
     * Create an item from DB
     *
     * @param array $data
     * @return mixed
     */
    public function create($data);

    /**
     * Delete an item from DB
     *
     * @param int $id
     * @return mixed
     */
    public function delete($id);

}
