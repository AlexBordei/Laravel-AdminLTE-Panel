<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('teacher_id');
            $table->dateTime('starting')->nullable();
            $table->dateTime('ending')->nullable();
            $table->enum('status', ['scheduled', 'canceled'])->default('scheduled');
            $table->string('google_event_id')->nullable();
            $table->timestamps();
        });

        Schema::table('reservations',function (Blueprint $table){
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('teacher_id')->references('id')->on('teachers')->cascadeOnDelete();

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
