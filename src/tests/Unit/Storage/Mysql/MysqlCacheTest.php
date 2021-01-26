<?php

namespace Unit\Storage\Mysql;

use Illuminate\Support\Facades\DB;
use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlFactory;
use PrionDevelopment\Setting\Storage\Mysql\MysqlString;

class MysqlCacheTest extends \SettingBaseTest
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('prion-setting.cache.enabled', true);
        $app['config']->set('prion-setting.storage', 'mysql');
    }

    /**
     * @test
     * @group cache
     */
    public function value_removed_from_database()
    {
        $setting = Setting::factory()->string()->create();

        /** @var $appSetting \PrionDevelopment\Setting\Setting */
        $appSetting = app('setting');
        $this->assertEquals($setting->value, $appSetting->get($setting->key));

        $settingTable = app('config')->get('prion-setting.database.tables.settings');
        DB::table($settingTable)->where('key', $setting->key)->delete();

        $this->assertEquals($setting->value, $appSetting->get($setting->key));

        $will_be_empty = DB::table($settingTable)->where('key', $setting->key)->first();
        $this->assertEmpty($will_be_empty);

        /** @var $appSettingNoCache MysqlFactory */
        $mysqlString = app(MysqlString::class);
        $this->assertEmpty($mysqlString->get($setting->key));
    }
}
