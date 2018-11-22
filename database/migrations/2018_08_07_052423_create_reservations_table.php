<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->bigInteger('user_id', false)->unsigned();
            $table->bigInteger('hotel_id', false)->unsigned();      // 
            $table->string('reservation_code')->nullable();            // 予約管理に利用する予約番号
            $table->dateTime('effective_ymd')->nullable();             // Y-m-d
            $table->dateTime('expire_ymd')->nullable();                // Y-m-d
            $table->tinyInteger('reservation_status')->nullable();     // 0:キャンセル 1:予約 2:変更あり
            $table->string('hotel_code')->nullable();
            $table->string('hotel_name')->nullable();
            $table->string('room_type_code', 12)->nullable();
            $table->string('room_name')->nullable();
            $table->tinyInteger('bed_type_code')->nullable();
            $table->tinyInteger('non_smoking')->nullable();     // 0:禁煙 1:喫煙
            $table->string('rate_plan_code', 15)->nullable();
            $table->string('rate_plan_name')->nullable();
            $table->text('rate_plan_description')->nullable();
            $table->tinyInteger('breakfast')->nullable();       // 0:なし 1:あり
            $table->tinyInteger('lunch')->nullable();           // 0:なし 1:あり
            $table->tinyInteger('dinner')->nullable();          // 0:なし 1:あり
            $table->integer('total_charge')->nullable();
            $table->string('arrival_time', 5)->nullable();
            $table->string('given_name_hurigana')->nullable();
            $table->string('last_name_hurigana')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('special_request')->nullable();
            $table->integer('s_ticket_amount');
            $table->integer('a_ticket_amount');
            $table->integer('b_ticket_amount');
            $table->integer('c_ticket_amount');
            $table->integer('d_ticket_amount');
            $table->string('reservation_group_id')->nullable();   // 一つの予約をまとめるコード

            $table->dateTime('reservation_dtm')->nullable();
            $table->timestamps();

            $table->index('reservation_code');
            $table->index('reservation_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
