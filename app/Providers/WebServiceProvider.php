<?php
namespace MusicCity\Providers;

use MusicCity\Services\Clients\DiscogsClient;

use Illuminate\Support\ServiceProvider;

class WebServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('MusicCity\Services\Clients\DiscogsClient', function ($app) {
            return new DiscogsClient(config('services.discogs.key'), config('services.discogs.secret'));
        });
    }
}