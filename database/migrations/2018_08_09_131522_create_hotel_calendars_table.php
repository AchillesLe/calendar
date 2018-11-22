<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_calendars', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('hotel_id', false)->unsigned();      // 
            $table->dateTime('date_ymd');         // Y-m-d
            $table->string('rank_status', 1);     // S:ハイシーズン A:土・祝前日 B:平日 C:オフシーズンの土・祝前日 D:オフシーズンの平日
            $table->timestamps();

            $table->index('hotel_id');
            $table->index('date_ymd');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_calendars');
    }
}
