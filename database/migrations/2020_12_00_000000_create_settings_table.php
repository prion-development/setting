<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
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
            $table->string('key', 500)
                ->nullable();
            $table->string('type', 20)
                ->nullable();
            $table->longText('value')
                ->nullable();
            $table->timestamps();

            $table->index('key', 'settings_index');
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
        return config('prion-setting.database.tables.settings');
    }

}
