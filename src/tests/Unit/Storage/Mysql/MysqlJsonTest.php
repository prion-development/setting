<?php

namespace Unit\Storage\Mysql;

use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlCachedFactory;
use PrionDevelopment\Setting\Storage\Mysql\MysqlJson;

class MysqlJsonTest extends \SettingBaseTest
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
     * @group mysql_json
     */
    public function is_using_json_class()
    {
        $settings = Setting::factory()->count(15)->json()->create();
        foreach ($settings as $setting) {
            /** @var $appSetting \PrionDevelopment\Setting\Setting */
            $appSetting = app('setting');
            $this->assertEquals("json", $appSetting->type($setting->value));

            $this->assertTrue($appSetting->storage()->factory($setting->value) instanceof MysqlJson);
        }
    }

    /**
     * @test
     * @group mysql
     * @group mysql_json
     */
    public function returns_json()
    {
        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $this->assertTrue($appSetting->storage() instanceof MysqlCachedFactory);

        $settings = Setting::factory()->count(15)->json()->create();
        foreach ($settings as $setting) {
            $this->assertTrue($appSetting->storage()->factoryFromKey($setting->key) instanceof MysqlJson);

            $this->assertEquals($setting->value, $appSetting->get($setting->key));
        }
    }

    /**
     * @test
     * @group mysql
     */
    public function will_create_json()
    {
        $key = 'key:json:json';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, json_encode(['valid_json']));

        $this->assertEquals(json_encode(['valid_json']), Setting::key($key)->first()->value);
        $this->assertEquals('json', Setting::key($key)->first()->type);
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

        $this->assertNotEquals('json', Setting::key($key)->first()->type);


        $key = 'key:integer:boolean';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, true);

        $this->assertNotEquals('json', Setting::key($key)->first()->type);


        $key = 'key:integer:integer';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 150);

        $this->assertNotEquals('json', Setting::key($key)->first()->type);


        $key = 'key:integer:object';

        $object = new \stdClass();
        $object->hello = 'hello';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, $object);

        $this->assertNotEquals('json', Setting::key($key)->first()->type);


        $key = 'key:integer:string';

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $appSetting->create($key, 'just a string');

        $this->assertNotEquals('json', Setting::key($key)->first()->type);
    }
}
