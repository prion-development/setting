<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Storage\SettingValueInterface;

class MysqlInteger extends MysqlAbstract implements SettingValueInterface
{
    CONST TYPE = 'json';

    public $type = 'integer';

    public function getValue(string $key)
    {
        return (int) $this->model($key)->value;
    }

    public function saveValue($value)
    {
        return $value;
    }

}
