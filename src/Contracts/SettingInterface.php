<?php

namespace PrionDevelopment\Setting\Contracts;

/**
 * This file is part of Setting,
 * a role & permission management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use PrionDevelopment\Setting\Models\Setting;

interface SettingInterface
{

    /**
     * Pull existing record or create a new record
     *
     * @param $key
     *
     * @return mixed|Setting
     */
    public function getOrCreate($key);


    /**
     * Retrieve a Setting Model Record
     *
     * @param $key
     *
     * @return mixed
     */
    public function get($key);


    /**
     * Retrieve the Value for a Key
     *
     * @param $key
     *
     * @return mixed
     */
    public function value($key);


    /**
     * Does this Key Exist?
     *
     * @param $key
     *
     * @return bool
     */
    public function exists($key): bool;

    /**
     * Throw an exception if the value is invalid.
     *
     * @param $value
     * @param string|null $key
     *
     * @return mixed
     */
    public function invalidException($value, string $key=null);

    /**
     * Does the Value and the Type Match?
     *
     * @return bool
     */
    public function valid(): bool;
}