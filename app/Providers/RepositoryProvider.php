<?php
namespace MusicCity\Providers;

use Artist;
use MusicCity\Repositories\ArtistRepository;

use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
	public function register()
	{
		$this->app->singleton('MusicCity\Repositories\ArtistRepository', function ($app) {
			return new ArtistRepository(new Artist());
		});
	}
}