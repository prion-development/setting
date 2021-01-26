<?php

namespace Unit\Storage\Mysql;

use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlCachedFactory;
use PrionDevelopment\Setting\Storage\Mysql\MysqlString;

class MysqlStringTest extends \SettingBaseTest
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
     * @group mysql_string
     */
    public function is_using_string_class()
    {
        $settings = Setting::factory()->count(15)->string()->create();
        foreach ($settings as $setting) {
            /** @var $appSetting \PrionDevelopment\Setting\Setting */
            $appSetting = app('setting');
            $this->assertEquals("string", $appSetting->type($setting->value));

            $this->assertTrue($appSetting->storage()->factory($setting->value) instanceof MysqlString);
        }
    }

    /**
     * @test
     * @group mysql
     * @group mysql_string
     */
    public function returns_string()
    {
        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $this->assertTrue($appSetting->storage() instanceof MysqlCachedFactory);

        $settings = Setting::factory()->count(15)->string()->create();
        foreach ($settings as $setting) {
            $this->assertTrue($appSetting->storage()->factoryFromKey($setting->key) instanceof MysqlString);

            $this->assertEquals($setting->value, $appSetting->get($setting->key));
            $this->assertTrue(is_string($appSetting->get($setting->key)));
        }
    }


    /**
     * @test
     * @group mysql
     */
    public function will_create_string()
    {
        $key = 'key:string:string';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 'simple string');

        $this->assertEquals('simple string', Setting::key($key)->first()->value);
        $this->assertEquals('simple string', $appSetting->get($key));
        $this->assertEquals('string', Setting::key($key)->first()->type);
    }

    /**
     * @test
     * @group mysql
     */
    public function will_not_create_other_values()
    {
        $key = 'key:string:array';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, ['hello']);

        $this->assertNotEquals('string', Setting::key($key)->first()->type);


        $key = 'key:string:boolean';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, true);

        $this->assertNotEquals('string', Setting::key($key)->first()->type);


        $key = 'key:string:integer';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 150);

        $this->assertNotEquals('string', Setting::key($key)->first()->type);


        $key = 'key:string:json';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, json_encode(['hello']));

        $this->assertFalse(is_integer(Setting::key($key)->first()->value));
        $this->assertNotEquals('string', Setting::key($key)->first()->type);


        $key = 'key:string:object';

        $object = new \stdClass();
        $object->hello = 'hello';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, $object);

        $this->assertNotEquals('string', Setting::key($key)->first()->type);
    }
}
