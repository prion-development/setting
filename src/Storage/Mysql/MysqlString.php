<?php

namespace PrionDevelopment\Setting\Storage\Mysql;

use PrionDevelopment\Setting\Storage\SettingValueInterface;

class MysqlString extends MysqlAbstract implements SettingValueInterface
{
    CONST TYPE = 'string';

    public $type = 'string';
}
