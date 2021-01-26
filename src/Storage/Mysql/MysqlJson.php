<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Storage\SettingValueInterface;

class MysqlJson extends MysqlAbstract implements SettingValueInterface
{
    CONST TYPE = 'json';

    public $type = 'json';

    public function getValue(string $key)
    {
        return $this->model($key)->value;
    }

    public function saveValue($value)
    {
        return $value;
    }
}
