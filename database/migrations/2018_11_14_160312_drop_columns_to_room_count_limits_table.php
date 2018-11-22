<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnsToRoomCountLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('room_count_limits', function (Blueprint $table) {
              $table->dropColumn('vacant_rooms');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('room_count_limits', function (Blueprint $table) {
            $table->integer('vacant_rooms')->after('room_name');
        });
    }
}
