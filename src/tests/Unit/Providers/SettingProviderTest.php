<?php

namespace Unit\Providers;

class SettingProviderTest extends \SettingBaseTest
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('prion-setting.storage', 'mysql');
    }

    /**
     * Make sure we use a tagged cache
     */
    public function testSettingSetStorage()
    {
        $this->assertTrue(app('setting')->storage() instanceof \PrionDevelopment\Setting\Storage\Mysql\MysqlFactory);
    }
}
