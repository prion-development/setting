<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Exceptions\SettingException;
use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\SettingValueInterface;
use PrionDevelopment\Setting\Storage\StorageInterface;
use PrionDevelopment\Setting\Traits\ValueTypeTrait;

class MysqlFactory implements StorageInterface
{
    use ValueTypeTrait;

    /** @var array */
    public $types = [
        'array' => MysqlArray::class,
        'boolean' => MysqlBoolean::class,
        'integer' => MysqlInteger::class,
        'json' => MysqlJson::class,
        'object' => MysqlObject::class,
        'string' => MysqlString::class,
    ];

    public function factory ($value): SettingValueInterface
    {
        $valueType = $this->valueToType($value);
        return app($this->types[$valueType]);
    }

    public function factoryFromKey(string $key): SettingValueInterface
    {
        $valueType = $this->model($key)->type;

        if (!array_key_exists($valueType, $this->types)) {
            throw new SettingException("Invalid setting type: {$valueType}");
        }

        return app($this->types[$valueType]);
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
    }

    public function create(string $key, $value)
    {
        return $this->factory($value)->create($key, $value);
    }

    public function createOrFail(string $key, $value)
    {
        return $this->factory($value)->createOrFail($key, $value);
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
        return $this->factoryFromKey($key)->exists($key);
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
        return $this->factoryFromKey($key)->filled($key);
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
     * @throws SettingException
     */
    public function get(string $key)
    {
        return $this->factoryFromKey($key)->get($key);
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws SettingException
     */
    public function getOrFail(string $key)
    {
        return $this->factoryFromKey($key)->getOrFail($key);
    }
}
