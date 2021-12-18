<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');
            $table->unsignedBigInteger('instrument_id');
            $table->unsignedBigInteger('room_id');
            $table->dateTime('starting');
            $table->dateTime('ending');
            $table->enum('status', ['new', 'confirmed', 'canceled'])->default('new');
            $table->string('google_event_id')->nullable();
            $table->timestamps();
        });

        Schema::table('events',function (Blueprint $table){
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('teacher_id')->references('id')->on('teachers');
            $table->foreign('instrument_id')->references('id')->on('instruments');
            $table->foreign('room_id')->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
