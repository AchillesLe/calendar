<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomCountLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_count_limits', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('hotel_id', false)->unsigned();      // 
            $table->string('room_name');
            $table->integer('vacant_rooms');    // 1年間に利用できる予約上限
            $table->timestamps();

            $table->index('hotel_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_count_limits');
    }
}
