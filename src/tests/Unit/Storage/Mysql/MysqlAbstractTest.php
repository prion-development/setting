<?php

namespace Unit\Storage\Mysql;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PrionDevelopment\Setting\Exceptions\SettingException;
use PrionDevelopment\Setting\Exceptions\SettingKeyNotFoundException;
use PrionDevelopment\Setting\Models\Setting;
use PrionDevelopment\Setting\Storage\Mysql\MysqlString;

class MysqlAbstractTest extends \SettingBaseTest
{
    use RefreshDatabase;

    /**
     * @test
     * @group mysql
     */
    public function filter_key()
    {
        $this->assertEquals("key", app(MysqlString::class)->filterKey('key '));
        $this->expectException(SettingException::class);
        $this->assertFalse(app(MysqlString::class)->filterKey(''));
    }

    /**
     * @test
     * @group mysql
     */
    public function does_not_exist()
    {
        $this->assertFalse(app(MysqlString::class)->exists('key'));
        $this->expectException(SettingException::class);
        $this->assertFalse(app(MysqlString::class)->exists(''));

        $this->expectException(ModelNotFoundException::class);
        app(MysqlString::class)->getOrFail('key');
    }

    /**
     * @test
     * @group mysql
     */
    public function cannot_get()
    {
        $this->assertNull(app(MysqlString::class)->get('key'));
        $this->expectException(SettingKeyNotFoundException::class);
        $this->assertNull(app(MysqlString::class)->getOrFail('key'));
    }

    /**
     * @test
     * @group mysql
     */
    public function is_not_filled()
    {
        $this->assertFalse(app(MysqlString::class)->filled('key'));
        $this->expectException(SettingException::class);
        app(MysqlString::class)->filled('');
    }

    /**
     * @test
     * @group mysql
     */
    public function cannot_create_exists()
    {
        $key = "string";
        Setting::factory()->make([
            'key' => $key,
            'type' => 'string',
            'value' => '',
        ]);

        $this->assertEquals("", app(MysqlString::class)->create($key, "")->value);

        $this->expectException(SettingException::class);
        app(MysqlString::class)->createOrFail($key, "");
    }

    /**
     * @test
     * @group mysql
     */
    public function get_key_exists()
    {
        $setting = Setting::factory()->create([
            'type' => 'string',
        ]);

        $key = $setting->key;

        $this->assertEquals($setting->value, app(MysqlString::class)->get($key));
        $this->assertEquals($setting->value, app(MysqlString::class)->getOrFail($key));
    }
}
