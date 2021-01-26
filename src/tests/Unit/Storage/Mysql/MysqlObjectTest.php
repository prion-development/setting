<?php

namespace Unit\Storage\Mysql;

use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlCachedFactory;
use PrionDevelopment\Setting\Storage\Mysql\MysqlObject;

class MysqlObjectTest extends \SettingBaseTest
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
     * @group mysql_object
     */
    public function is_using_object_class()
    {
        $settings = Setting::factory()->count(15)->object()->create();
        foreach ($settings as $setting) {
            $value = json_decode($setting->value);

            /** @var $appSetting \PrionDevelopment\Setting\Setting */
            $appSetting = app('setting');
            $this->assertEquals("object", $appSetting->type($value));

            $this->assertTrue($appSetting->storage()->factory($value) instanceof MysqlObject);
        }
    }

    /**
     * @test
     * @group mysql
     * @group mysql_object
     */
    public function returns_object()
    {
        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $this->assertTrue($appSetting->storage() instanceof MysqlCachedFactory);

        $settings = Setting::factory()->count(15)->object()->create();
        foreach ($settings as $setting) {
            $this->assertTrue($appSetting->storage()->factoryFromKey($setting->key) instanceof MysqlObject);

            $this->assertEquals(json_decode($setting->value), $appSetting->get($setting->key));
            $this->assertTrue(is_object($appSetting->get($setting->key)));
        }
    }


    /**
     * @test
     * @group mysql
     */
    public function will_create_object()
    {
        $key = 'key:object:object';

        $object = new \stdClass();
        $object->hello = 'hello';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, $object);

        $this->assertEquals($object, json_decode(Setting::key($key)->first()->value));
        $this->assertEquals($object, $appSetting->get($key));
        $this->assertEquals('object', Setting::key($key)->first()->type);
    }

    /**
     * @test
     * @group mysql
     */
    public function will_not_create_other_values()
    {
        $key = 'key:object:array';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, ['hello']);

        $this->assertNotEquals('object', Setting::key($key)->first()->type);


        $key = 'key:object:boolean';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, true);

        $this->assertNotEquals('object', Setting::key($key)->first()->type);


        $key = 'key:object:integer';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 150);

        $this->assertNotEquals('object', Setting::key($key)->first()->type);


        $key = 'key:integer:json';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, json_encode(['hello']));

        $this->assertFalse(is_integer(Setting::key($key)->first()->value));
        $this->assertNotEquals('object', Setting::key($key)->first()->type);


        $key = 'key:object:string';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 'just a string');

        $this->assertNotEquals('object', Setting::key($key)->first()->type);
    }
}
