<?php

namespace PrionDevelopment\Setting\Traits;

use PrionDevelopment\Setting\Exceptions\SettingException;

trait ValueTypeTrait
{
    /**
     * Please Note, these are processed in the order below. The Order is important!
     *
     * @var array
     */
    protected $valueTypes = [
        'array',
        'object',
        'json',
        'boolean',
        'integer',
        'string',
    ];

    /**
     * Convert a Given Value to a Type
     *
     * @param $value
     *
     * @return string
     * @throws SettingException
     */
    public function valueToType($value): string
    {
        foreach ($this->valueTypes as $type) {
            $checkMethod = "is" . ucfirst($type);
            if ($this->{$checkMethod}($value) === true) {
                return $type;
            }
        }

        throw new SettingException("Cannot detect the type of value");
    }

    protected function isArray($value): bool
    {
        return is_array($value);
    }

    protected function isBoolean($value): bool
    {
        return $value === true || $value === false;
    }

    protected function isInteger($value): bool
    {
        return is_integer($value);
    }

    protected function isJson($value): bool
    {
        if ($this->isInteger($value) || $this->isBoolean($value)) {
            return false;
        }

        json_decode($value);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    protected function isObject($value): bool
    {
        return is_object($value);
    }

    protected function isString($value): bool
    {
        return is_string($value);
    }
}
