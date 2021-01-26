<?php

class SettingConfigTest extends SettingTestCase
{
    public function testRunMembraneMigrations()
    {
        $accountTable = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name='migrations'");

        $this->assertEquals(1, count($accountTable), 'Migrations failed to run');
        $this->assertEquals('migrations', reset($accountTable)->name, 'Migrations failed to run');

        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table'");
        $this->assertTrue(count($tables) >= 4, 'Tables were not migrated');
    }
}