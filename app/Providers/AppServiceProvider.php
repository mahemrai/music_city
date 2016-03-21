<?php

namespace MusicCity\Providers;

use MusicCity\Artist;
use MusicCity\Services\App\ListGenerator;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Artist::deleting(function ($artist) {
            foreach ($artist->albums() as $album) {
                $album->tracks()->delete();
                $album->delete();
            }
        });

        $this->app->singleton('MusicCity\Services\App\ListGenerator', function ($app) {
            return new ListGenerator();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
