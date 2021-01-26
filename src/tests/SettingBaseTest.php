<?php

class SettingBaseTest extends SettingTestCase
{
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('prion-setting.storage', 'mysql');
    }

    /**
     * Make sure the config is loading
     */
    public function testConfigExists(): void
    {
        $config = app('config')->get('prion-setting');
        $storage = app('config')->get('prion-setting.storage');

        $this->assertNotEmpty($config, 'Configuration is not setup properly');
        $this->assertNotEmpty($storage, 'Setting storage is not setup properly');
    }
}
