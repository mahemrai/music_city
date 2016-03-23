<?php
namespace MusicCity\Repositories;

use Artist;
use MusicCity\Respositories\RepositoryInterface;

class ArtistRepository implements RepositoryInterface
{
	protected $artist;

	public function __construct(Artist $artist)
	{
		$this->artist = $artist;
	}

	public function create()
	{

	}

	public function update()
	{

	}

	public function delete()
	{
		
	}
}