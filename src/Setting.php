<?php

namespace PrionDevelopment\Setting;

use PrionDevelopment\Setting\Storage\Mysql\MysqlFactory;
use PrionDevelopment\Setting\Storage\StorageInterface;

/**
 * This class is the main entry point of Prion Setting. Usually this the interaction
 * with this class will be done through the Setting Facade
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

class Setting
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /** @var StorageInterface|MysqlFactory */
    protected $storageInterface;

    /**
     * Setting constructor.
     *
     * @param $app
     * @param StorageInterface $storageInterface
     */
    public function __construct($app, StorageInterface $storage)
    {
        $this->app = $app;
        $this->storageInterface = $storage;
    }

    /**
     * Store a Key/Value
     *
     * @param string $key
     * @param $value
     *
     * @return mixed
     */
    public function create(string $key, $value)
    {
        return $this->storageInterface->create($key, $value);
    }

    /**
     * Store a Key/Value
     *
     * @param string $key
     * @param $value
     *
     * @return mixed
     */
    public function createOrFail(string $key, $value)
    {
        return $this->storageInterface->createOrFail($key, $value);
    }

    /**
     * Does the key exist?
     *
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        return $this->storageInterface->exists($key);
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
        return $this->storageInterface->filled($key);
    }

    /**
     * Delete the Key/Value
     *
     * @param string $key
     */
    public function forget(string $key): void
    {
        $this->storageInterface->forget($key);
    }

    /**
     * Pull a Value
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->storageInterface->get($key);
    }

    /**
     * Pull a Value
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getOrFail(string $key)
    {
        return $this->storageInterface->getOrFail($key);
    }

    public function storage()
    {
        return $this->storageInterface;
    }

    /**
     * The value type (string, boolean, json, object, array, etc)
     *
     * @param $value
     *
     * @return string|null
     * @throws Exceptions\SettingException
     */
    public function type($value): ?string
    {
        return $this->storageInterface->valueToType($value);
    }
}
