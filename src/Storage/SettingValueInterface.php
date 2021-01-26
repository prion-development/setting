<?php

namespace PrionDevelopment\Setting\Storage;

interface SettingValueInterface
{
    public function model(string $key);

    public function create(string $key, $value);

    public function createOrFail(string $key, $value);

    public function exists(string $key): bool;

    public function filled(string $key): bool;

    public function forget(string $key);

    public function get(string $key);

    public function getOrFail(string $key);

    public function getValue(string $key);

    public function saveValue($value);
}
