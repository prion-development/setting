<?php

namespace Unit\Storage\Mysql;

use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlArray;
use PrionDevelopment\Setting\Storage\Mysql\MysqlCachedFactory;

class MysqlArrayTest extends \SettingBaseTest
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('prion-setting.cache.enabled', true);
        $app['config']->set('prion-setting.storage', 'mysql');
    }

    /**
     * @test
     * @group mysql
     * @group mysql_array
     */
    public function is_using_array_class()
    {
        $settings = Setting::factory()->count(15)->array()->create();
        foreach ($settings as $setting) {
            $value = json_decode($setting->value);

            /** @var $appSetting \PrionDevelopment\Setting\Setting */
            $appSetting = app('setting');
            $this->assertEquals("array", $appSetting->type($value));

            $this->assertTrue($appSetting->storage()->factory($value) instanceof MysqlArray);
        }
    }

    /**
     * @test
     * @group mysql
     * @group mysql_array
     */
    public function returns_array()
    {
        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $this->assertTrue($appSetting->storage() instanceof MysqlCachedFactory);

        $settings = Setting::factory()->count(15)->array()->create();
        foreach ($settings as $setting) {
            $this->assertTrue($appSetting->storage()->factoryFromKey($setting->key) instanceof MysqlArray);

            $this->assertEquals(json_decode($setting->value), array_values($appSetting->get($setting->key)));
            $this->assertTrue(is_array($appSetting->get($setting->key)));
        }
    }

    /**
     * @test
     * @group mysql
     */
    public function will_create_array()
    {
        $key = 'key:array';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, ['hello']);

        $this->assertEquals(["hello"], json_decode(Setting::key($key)->first()->value));
        $this->assertEquals('array', Setting::key($key)->first()->type);
    }

    /**
     * @test
     * @group mysql
     */
    public function will_not_create_other_values()
    {
        $key = 'key:array:boolean';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 1);

        $this->assertFalse(is_array(Setting::key($key)->first()->value));
        $this->assertNotEquals('array', Setting::key($key)->first()->type);


        $key = 'key:array:integer';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 56);

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('array', Setting::key($key)->first()->type);


        $key = 'key:array:json';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, json_encode(['hello']));

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('array', Setting::key($key)->first()->type);


        $key = 'key:array:object';

        $object = new \stdClass();
        $object->hello = 'hello';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, $object);

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('array', Setting::key($key)->first()->type);
    }
}
