<?php

namespace PrionDevelopment\Setting;

/**
 * This file is part of Setting,
 * a key/value management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use PrionDevelopment\Setting\Providers\StorageProvider;

class SettingServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /** @var array */
    protected $setup = [
        \PrionDevelopment\Setting\Providers\Config::class,
        StorageProvider::class,
    ];

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerSetting();
        $this->registerProviders();
    }

    /**
     * Register Setting in Laravel/Lumen
     *
     */
    private function registerSetting(): void
    {
        $this->app->bind('setting', function ($app) {
            return app(\PrionDevelopment\Setting\Setting::class, ['app' => $app]);
        });

        $loader = \Illuminate\Foundation\AliasLoader::getInstance();
        $loader->alias('Setting', \PrionDevelopment\Setting\SettingFacade::class);
    }

    /**
     * Register Additional Providers, such as config setup
     * and command setup
     */
    private function registerProviders(): void
    {
        foreach($this->setup as $setup) {
            $this->app->register($setup);
        }
    }
}
