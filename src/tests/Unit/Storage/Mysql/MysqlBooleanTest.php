<?php

namespace Unit\Storage\Mysql;

use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlBoolean;
use PrionDevelopment\Setting\Storage\Mysql\MysqlCachedFactory;

class MysqlBooleanTest extends \SettingBaseTest
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
     * @group mysql_boolean
     */
    public function is_using_boolean_class()
    {
        $settings = Setting::factory()->count(15)->boolean()->create();
        foreach ($settings as $setting) {
            /** @var $appSetting \PrionDevelopment\Setting\Setting */
            $appSetting = app('setting');
            $this->assertEquals("boolean", $appSetting->type($setting->value));

            $this->assertTrue($appSetting->storage()->factory($setting->value) instanceof MysqlBoolean);
        }
    }

    /**
     * @test
     * @group mysql
     * @group mysql_boolean
     */
    public function returns_boolean()
    {
        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $this->assertTrue($appSetting->storage() instanceof MysqlCachedFactory);

        $settings = Setting::factory()->count(15)->boolean()->create();
        foreach ($settings as $setting) {
            $this->assertTrue($appSetting->storage()->factoryFromKey($setting->key) instanceof MysqlBoolean);

            $this->assertIsBool($appSetting->get($setting->key));

            $this->assertEquals($setting->value, $appSetting->get($setting->key));
            $this->assertTrue(is_bool($appSetting->get($setting->key)));
        }
    }

    /**
     * @test
     * @group mysql
     */
    public function will_create_boolean()
    {
        $key = 'key:boolean:true';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, true);

        $this->assertEquals(1, Setting::key($key)->first()->value);
        $this->assertEquals('boolean', Setting::key($key)->first()->type);


        $key = 'key:boolean:false';
        $appSetting->create($key, false);

        $this->assertEquals(0, Setting::key($key)->first()->value);
        $this->assertEquals('boolean', Setting::key($key)->first()->type);

    }

    /**
     * @test
     * @group mysql
     */
    public function will_not_create_other_values()
    {
        $key = 'key:boolean:string';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 'just a string');

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('boolean', Setting::key($key)->first()->type);


        $key = 'key:boolean:array';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, ['hello']);

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('boolean', Setting::key($key)->first()->type);


        $key = 'key:boolean:integer';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 56);

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('boolean', Setting::key($key)->first()->type);


        $key = 'key:boolean:json';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, json_encode(['hello']));

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('boolean', Setting::key($key)->first()->type);


        $key = 'key:boolean:object';

        $object = new \stdClass();
        $object->hello = 'hello';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, $object);

        $this->assertFalse(is_bool(Setting::key($key)->first()->value));
        $this->assertNotEquals('boolean', Setting::key($key)->first()->type);
    }
}
