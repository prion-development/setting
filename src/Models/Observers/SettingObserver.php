<?php

namespace PrionDevelopment\Setting\Models\Observers;

/**
 * This file is part of Setting,
 * a setting management solution for Laravel.
 *
 * @license MIT
 * @company Prion Development
 * @package Setting
 */

use PrionDevelopment\Setting\Models\Setting;

class SettingObserver
{

    /**
     * Cache Settings
     *
     * @var
     */
    protected $cache;

    public function __construct()
    {
        $prefix = app('config')->get('prion-setting.cache.prefix');
        $this->cache = app('cache')->tags($prefix);
    }

    /**
     * Observer when a Setting is Created
     *
     * @param Setting $setting
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function created(Setting $setting)
    {
        $this->log($setting->id);
    }


    /**
     * Observer when Setting is Updated
     *
     * @param Setting $setting
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function updated(Setting $setting)
    {
        $original = $setting->getOriginal();
        $this->log($setting->id, $original->value);
        $this->clearCache($setting->key);
    }

    public function deleted(Setting $setting)
    {
        $this->clearCache($setting->key);
    }


    /**
     * Log the Setting Change
     *
     * @param $setting_id
     * @param string $previous
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function log($setting_id, $previous=''): void
    {
        // Check if Logging is Enabled
        if (!config('setting.enable_logging')) {
            return;
        }

        $auth = app()->make('auth');
        $user_id = $auth->check() ? $auth->user()->id : 0;
        Setting::insert([
            'user_id' => $user_id,
            'setting_id' => $setting_id,
            'previous' => $previous,
        ]);
    }


    /**
     * Clear the Cache when Values are Updated
     *
     * @param $key
     */
    private function clearCache($key): void
    {
        $this->cache->forget($key);
        $this->cache->forget(Setting::CACHE_TAG_EXISTS . $key);
        $this->cache->forget(Setting::CACHE_TAG_FILLED . $key);
        $this->cache->forget(Setting::CACHE_TAG_MODEL . $key);
    }

}
