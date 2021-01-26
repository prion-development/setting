<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Storage\SettingValueInterface;

class MysqlObject extends MysqlAbstract implements SettingValueInterface
{
    CONST TYPE = 'object';

    public $type = 'object';

    public function getValue(string $key)
    {
        return json_decode($this->model($key)->value);
    }

    public function saveValue($value)
    {
        return json_encode($value);
    }
}
