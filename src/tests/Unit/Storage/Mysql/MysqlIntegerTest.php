<?php

namespace Unit\Storage\Mysql;

use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlCachedFactory;
use PrionDevelopment\Setting\Storage\Mysql\MysqlInteger;

class MysqlIntegerTest extends \SettingBaseTest
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
     * @group mysql_integer
     */
    public function is_using_integer_class()
    {
        $settings = Setting::factory()->count(15)->integer()->create();
        foreach ($settings as $setting) {
            /** @var $appSetting \PrionDevelopment\Setting\Setting */
            $appSetting = app('setting');
            $this->assertEquals("integer", $appSetting->type($setting->value));

            $this->assertTrue($appSetting->storage()->factory($setting->value) instanceof MysqlInteger);
        }
    }

    /**
     * @test
     * @group mysql
     * @group mysql_integer
     */
    public function returns_integer()
    {
        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $this->assertTrue($appSetting->storage() instanceof MysqlCachedFactory);

        $settings = Setting::factory()->count(15)->integer()->create();
        foreach ($settings as $setting) {
            $this->assertTrue($appSetting->storage()->factoryFromKey($setting->key) instanceof MysqlInteger);

            $this->assertEquals($setting->value, $appSetting->get($setting->key));
            $this->assertTrue(is_integer($appSetting->get($setting->key)));
        }
    }

    /**
     * @test
     * @group mysql
     */
    public function will_create_integer()
    {
        $key = 'key:integer:125';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 125);

        $this->assertEquals(125, Setting::key($key)->first()->value);
        $this->assertEquals('integer', Setting::key($key)->first()->type);
    }

    /**
     * @test
     * @group mysql
     */
    public function will_not_create_other_values()
    {
        $key = 'key:integer:array';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, ['hello']);

        $this->assertFalse(is_integer(Setting::key($key)->first()->value));
        $this->assertNotEquals('integer', Setting::key($key)->first()->type);


        $key = 'key:integer:boolean';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, true);

        $this->assertFalse(is_integer(Setting::key($key)->first()->value));
        $this->assertNotEquals('integer', Setting::key($key)->first()->type);


        $key = 'key:integer:json';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, json_encode(['hello']));

        $this->assertFalse(is_integer(Setting::key($key)->first()->value));
        $this->assertNotEquals('integer', Setting::key($key)->first()->type);


        $key = 'key:integer:object';

        $object = new \stdClass();
        $object->hello = 'hello';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, $object);

        $this->assertFalse(is_integer(Setting::key($key)->first()->value));
        $this->assertNotEquals('integer', Setting::key($key)->first()->type);


        $key = 'key:integer:string';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 'just a string');

        $this->assertFalse(is_integer(Setting::key($key)->first()->value));
        $this->assertNotEquals('integer', Setting::key($key)->first()->type);
    }
}
