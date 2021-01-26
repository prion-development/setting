<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Storage\SettingValueInterface;

class MysqlArray extends MysqlAbstract implements SettingValueInterface
{
    CONST TYPE = 'array';

    public $type = 'array';

    public function getValue(string $key)
    {
        return json_decode($this->model($key)->value);
    }

    public function saveValue($value)
    {
        return json_encode($value);
    }
}
