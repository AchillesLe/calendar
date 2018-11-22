<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('membership_id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->tinyInteger('status');  // 0:invalid 1:valid
            $table->string('given_name');
            $table->string('surname');
            $table->string('given_name_hurigana');
            $table->string('last_name_hurigana');
            $table->string('gender');
            $table->date('birth_date');
            $table->string('postal_code');
            $table->string('address1');
            $table->string('address2');
            $table->string('address3');
            $table->string('address4');
            $table->string('tel');
            $table->string('cell_tel');
            $table->string('pc_email');
            $table->string('mobile_email');
            $table->string('office_postal_code');
            $table->string('office_address1');
            $table->string('office_address2');
            $table->string('office_address3');
            $table->string('office_address4');
            $table->string('office_tel');
            $table->string('company_name');
            $table->string('occupation');
            $table->string('employee_level');
            $table->rememberToken();
            $table->timestamps();
            $table->index('membership_id', 'email');
        });
        DB::statement('ALTER TABLE users MODIFY membership_id varchar(255) BINARY');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
