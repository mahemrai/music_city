<?php
namespace MusicCity\Repositories;

interface RepositoryInterface
{
	public function create($data, $id = null);
	public function remove($id);
}