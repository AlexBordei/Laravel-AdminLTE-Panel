<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->date('birth_date');
            $table->string('google_calendar_id')->nullable();
            $table->string('instrument_ids')->nullable();
            $table->timestamps();
        });

        Schema::table('teachers',function (Blueprint $table){

            $table->unsignedBigInteger('room_id')->nullable();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teachers');
    }
}
