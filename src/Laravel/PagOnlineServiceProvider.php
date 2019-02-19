<?php

namespace PagOnline\Laravel;

use PagOnline\IgfsCgFactory;
use Illuminate\Support\ServiceProvider;

/**
 * Class PagOnlineServiceProvider.
 */
class PagOnlineServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPublishing();
    }

    /**
     * Register resources that can be published.
     */
    public function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/../../config/pagonline.php' => config_path('pagonline.php'),
        ], 'pagonline-config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/pagonline.php', 'pagonline'
        );
        $this->registerFactory();
    }

    /**
     * Register Factory Facade.
     */
    public function registerFactory()
    {
        $this->app->singleton('igfscg', function () {
            return new IgfsCgFactory();
        });
        $this->app->alias('igfscg', IgfsCgFactory::class);
    }
}
