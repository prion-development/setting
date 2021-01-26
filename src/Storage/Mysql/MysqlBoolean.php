<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Storage\SettingValueInterface;

class MysqlBoolean extends MysqlAbstract implements SettingValueInterface
{
    CONST TYPE = 'boolean';

    public $type = 'boolean';

    public function getValue(string $key)
    {
        return $this->model($key)->value === '1';
    }

    public function saveValue($value)
    {
        return $this->transform($value) ? '1' : '0';
    }

    /**
     * Transform a Value into '1' or '0'
     *
     * @param $value
     *
     * @return bool
     */
    public function transform($value): bool
    {
        return $value === true || strtolower($value) === 'true' || $value === 1 || $value === '1';
    }
}
