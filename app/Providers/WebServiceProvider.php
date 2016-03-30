<?php
namespace MusicCity\Providers;

use MusicCity\Services\Clients\DiscogsClient;
use MusicCity\Services\Clients\LastfmClient;
use MusicCity\Services\Clients\SongkickClient;

use Illuminate\Support\ServiceProvider;

/**
 * @package MusicCity
 * @uses    ServiceProvider
 * @author  Mahendra Rai
 */
class WebServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('MusicCity\Services\Clients\DiscogsClient', function ($app) {
            return new DiscogsClient(config('services.discogs.key'), config('services.discogs.secret'));
        });

        $this->app->singleton('MusicCity\Services\Clients\LastfmClient', function ($app) {
            return new LastfmClient(config('services.lastfm.key'));
        });

        $this->app->singleton('MusicCity\Services\Clients\SongkickClient', function ($app) {
            return new SongkickClient(config('services.songkick.key'));
        });
    }
}