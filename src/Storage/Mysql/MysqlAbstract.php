<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use \Illuminate\Database\Eloquent\ModelNotFoundException;
use PrionDevelopment\Setting\Exceptions\SettingException;
use PrionDevelopment\Setting\Exceptions\SettingKeyNotFoundException;
use PrionDevelopment\Setting\Models\Setting;

abstract class MysqlAbstract
{
    /**
     * @param string $key
     * @param $value
     *
     * @throws SettingException
     */
    public function create(string $key, $value)
    {
        $key = $this->filterKey($key);

        if ($this->exists($key)) {
            return;
        }

        return Setting::create([
            'key' => $key,
            'type' => $this->type,
            'value' => $this->saveValue($value),
        ]);
    }

    public function createOrFail(string $key, $value)
    {
        $key = $this->filterKey($key);

        if ($this->exists($key)) {
            throw new SettingException("Key {$key} already exists");
        }

        $this->create($key, $value);
    }

    /**
     * Does the Key Exist in the database?
     *
     * @param string $key
     *
     * @return bool
     * @throws SettingException
     */
    public function exists(string $key): bool
    {
        $key = $this->filterKey($key);

        try {
            $this->model($key);
            return true;
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    /**
     * Does the key exist and is the value filled?
     *
     * @param string $key
     *
     * @return bool
     * @throws SettingException
     */
    public function filled(string $key): bool
    {
        try {
            $key = $this->filterKey($key);
            $setting = $this->model($key);
            return !empty($setting->value) && trim($setting->value) !== '';
        } catch (ModelNotFoundException $e) {
            return false;
        }
    }

    public function filterKey(string $key): string
    {
        $key = trim($key);

        if (empty($key)) {
            throw new SettingException("Key cannot be empty");
        }

        return $key;
    }

    public function forget(string $key)
    {
        Setting::where('key', $key)->delete();
    }

    /**
     * Pull the Value from the Model
     *
     * @param string $key
     *
     * @return mixed|null
     * @throws SettingException
     */
    public function get(string $key)
    {
        try {
            return $this->getValue($key);
        } catch (ModelNotFoundException $e) {
            return null;
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     * @throws SettingException
     * @throws SettingKeyNotFoundException
     */
    public function getOrFail(string $key)
    {
        try {
            return $this->getValue($key);
        } catch (ModelNotFoundException $e) {
            $this->exceptionNotFound($key);
        }
    }

    /**
     * Retrieve and Properly format the Value
     *
     * @param string $key
     *
     * @return mixed
     * @throws SettingException
     */
    public function getValue(string $key)
    {
        return $this->model($key)->value;
    }

    /**
     * Update and Properly Save the Value
     *
     * @param $value
     *
     * @return mixed
     */
    public function saveValue($value)
    {
        return $value;
    }

    /**
     * Pull the Model for the Key
     *
     * @param string $key
     *
     * @return mixed
     * @throws SettingException
     */
    public function model(string $key)
    {
        $key = $this->filterKey($key);
        return Setting::key($key)->firstOrFail();
    }

    /**
     * @param $key
     *
     * @throws SettingKeyNotFoundException
     */
    public function exceptionNotFound($key): void
    {
        throw new SettingKeyNotFoundException("Key {$key} not found");
    }
}
