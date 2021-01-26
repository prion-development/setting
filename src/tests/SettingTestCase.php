<?php

abstract class SettingTestCase extends \Orchestra\Testbench\TestCase
{
    /** @var bool */
    protected static $setupFactories = false;

    public function setUp(): void
    {
        parent::setUp();
        $this->factories();
        $this->migrations();
    }

    protected function factories()
    {
        if (self::$setupFactories === false) {
            $factoriesPath = realpath(__DIR__.'/../../database/factories');
            $this->withFactories($factoriesPath);
            self::$setupFactories = true;
        }
    }

    protected function migrations()
    {
        $migrationsPath = realpath(__DIR__.'/../../database/migrations');

        if (!is_dir($migrationsPath)) {
            throw new \PrionDevelopment\Setting\Exceptions\SettingException("Migrations path incorrect.");
        }

        $this->loadMigrationsFrom($migrationsPath);
    }

    protected function getPackageProviders($app)
    {
        return ['\PrionDevelopment\Setting\SettingServiceProvider'];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}