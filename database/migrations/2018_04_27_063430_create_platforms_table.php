<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('platforms')){
            Schema::create('platforms', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('department');
                $table->integer('platform');
                $table->integer('company');
                $table->integer('user_id');
                $table->integer('criticality');
                $table->char('department_name',255);
                $table->char('platform_name',255);
                $table->char('company_name',255);
                $table->timestamps();
            });
        };
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('platforms');
    }
}
