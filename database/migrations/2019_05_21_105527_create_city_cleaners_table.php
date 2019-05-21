<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCityCleanersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('city_cleaners', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('cleaner_id');
            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('cleaner_id')
                ->references('id')->on('cleaners')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('city_cleaners');
    }
}
