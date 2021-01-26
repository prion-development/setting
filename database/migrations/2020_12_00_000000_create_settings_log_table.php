<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table)
            {
                $table->increments('id');
                $table->integer('user_id')->nullable()->default('0');
                $table->unsignedBigInteger('setting_id');
                $table->foreign('setting_id')->references('id')->on($this->settingsTableName())->onDelete('cascade');

                $table->longText('previous');
                $table->timestamp('created_at')->useCurrent();

                $table->index(['user_id', 'setting_id'], 'settings_log_user_id_index');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->tableName());
    }

    private function tableName(): string
    {
        return config('prion-setting.database.tables.settings_log');
    }

    public function settingsTableName(): string
    {
        return config('prion-setting.database.tables.settings');
    }
}
