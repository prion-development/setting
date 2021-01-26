<?php

namespace PrionDevelopment\Setting\Models;

/**
 * This file is part of Setting,
 * a setting management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PrionDevelopment\Setting\Database\Factories\SettingFactory;
use PrionDevelopment\Setting\Exceptions\SettingException;

class Setting extends Model
{
    use HasFactory;

    CONST CACHE_TAG_EXISTS = "exists:";
    CONST CACHE_TAG_FILLED = "filled:";
    CONST CACHE_TAG_MODEL = "model:";

    protected $fillable = [
        'key',
        'type',
        'value',
    ];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * Creates a new instance of the model.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('prion-setting.database.tables.settings');
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return SettingFactory::new();
    }

    public function scopeKey(Builder $builder, string $key)
    {
        $key = $this->filterKey($key);
        return $builder->where('key', $key);
    }

    public function filterKey(string $key): string
    {
        $key = trim($key);

        if (empty($key)) {
            throw new SettingException("Key cannot be empty");
        }

        return $key;
    }
}
