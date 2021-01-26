<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Models\Setting;

class MysqlCachedFactory extends MysqlFactory
{
    /**
     * Cache Settings
     *
     * @var
     */
    protected $cache;

    public function __construct()
    {
        $prefix = app('config')->get('prion-setting.cache.prefix');
        $this->cache = app('cache')->tags($prefix);
    }

    /**
     * @return int
     */
    public function ttl(): int
    {
        return app('config')->get('prion-setting.cache.ttl');
    }

    /**
     * Pull the Model for the Key
     *
     * @param $key
     *
     * @return null|Setting
     */
    public function model(string $key)
    {
        return Setting::key($key)->firstOrFail();
        return $this->cache->remember($this->keyModel($key), $this->ttl(), function () use ($key) {
            return Setting::key($key)->firstOrFail();
        });
    }


    public function key(string $key): string
    {
        return $key;
    }

    public function keyExists(string $key): string
    {
        return Setting::CACHE_TAG_EXISTS . $this->key($key);
    }

    public function keyFilled(string $key): string
    {
        return Setting::CACHE_TAG_FILLED . $this->key($key);
    }

    public function keyModel(string $key): string
    {
        return Setting::CACHE_TAG_MODEL . $this->key($key);
    }

    /**
     * Does the Key Exist in the database?
     *
     * @param $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        return $this->cache->remember($this->keyExists($key), $this->ttl(), function () use ($key) {
            return $this->factoryFromKey($key)->exists($key);
        });
    }

    /**
     * Does the key exist and is the value filled?
     *
     * @param $key
     *
     * @return bool
     */
    public function filled(string $key): bool
    {
        return $this->cache->remember($this->keyFilled($key), $this->ttl(), function () use ($key) {
            return $this->factoryFromKey($key)->filled($key);
        });
    }

    public function forget(string $key)
    {
        return $this->factoryFromKey($key)->forget($key);
    }

    /**
     * Pull the Value from the Model
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->cache->remember($this->key($key), $this->ttl(), function () use ($key) {
            return $this->factoryFromKey($key)->get($key);
        });
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getOrFail(string $key)
    {
        return $this->cache->remember($this->key($key), $this->ttl(), function () use ($key) {
            return $this->factoryFromKey($key)->getOrFail($key);
        });
    }
}
