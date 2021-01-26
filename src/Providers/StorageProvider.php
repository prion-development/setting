<?php

namespace PrionDevelopment\Setting\Providers;

use Illuminate\Support\ServiceProvider;
use PrionDevelopment\Setting\Exceptions\SettingException;
use PrionDevelopment\Setting\Storage\Mysql\MysqlCachedFactory;
use PrionDevelopment\Setting\Storage\Mysql\MysqlFactory;
use PrionDevelopment\Setting\Storage\StorageInterface;

class StorageProvider extends ServiceProvider implements ProviderInterface
{
    /**
     * Default Storage
     *
     * @options - cache, redis, mysql
     *
     * @var string
     */
    private $defaultStorage = 'mysql';

    public function boot(): void
    {
    }

    public function register(): void
    {
        $this->registerStorage();
    }

    private function storage(): string
    {
        return $this->configStorage() ?? $this->defaultStorage;
    }

    private function configStorage(): ?string
    {
        $storage = config('prion-setting.storage');
        return strtolower($storage);
    }

    private function registerStorage(): void
    {
        $storage = $this->storage();
        switch($storage) {
            case 'mysql':
                if (app('config')->get('prion-setting.cache.enabled')) {
                    $this->app->bind(StorageInterface::class, MysqlCachedFactory::class);
                    return;
                }

                $this->app->bind(StorageInterface::class, MysqlFactory::class);
                return;

            default:
                throw new SettingException("Prion storage value ({$this->configStorage()}) is invalid");
        }
    }
}