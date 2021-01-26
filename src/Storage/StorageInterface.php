<?php

namespace PrionDevelopment\Setting\Storage;

interface StorageInterface
{
    public function factory ($value): SettingValueInterface;

    public function factoryFromKey(string $key): SettingValueInterface;
}
