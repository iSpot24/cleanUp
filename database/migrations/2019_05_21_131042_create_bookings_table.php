<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cleaner_id');
            $table->date('date');
            $table->unsignedInteger('city_id');
            $table->unsignedInteger('customer_id');
            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')->on('cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('cleaner_id')
                ->references('id')->on('cleaners')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreign('customer_id')
                ->references('id')->on('customers')
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
        Schema::dropIfExists('bookings');
    }
}
