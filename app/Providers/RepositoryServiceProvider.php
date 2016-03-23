<?php
namespace MusicCity\Providers;

use MusicCity\Artist;
use MusicCity\Repositories\ArtistRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->bind('MusicCity\Repositories\RepositoryInterface', 'MusicCity\Repositories\ArtistRepository');
        $this->app->bind('MusicCity\Repositories\RepositoryInterface', 'MusicCity\Repositories\AlbumRepository');
	}
}