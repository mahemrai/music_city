<?php
namespace MusicCity\Repositories;

/**
 * @package MusicCity
 * @author  Mahendra Rai
 */
interface RepositoryInterface
{
    public function getById($id);

    public function getByField($value, $field);

    public function getSorted($field = null, $orderOption = null, $limit = null, $paginate = false);

	public function create($data, $id = null);

	public function remove($id);
}