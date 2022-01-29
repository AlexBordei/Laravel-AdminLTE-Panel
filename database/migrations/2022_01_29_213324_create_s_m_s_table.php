<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSMSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SMS', function (Blueprint $table) {
            $table->id();
            $table->string('from');
            $table->string('to');
            $table->string('message');
            $table->enum('status', ['pending', 'sent', 'error'])->default('pending');
            $table->string('error')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SMS');
    }
}
// TODO: create endpoint for updating the message status from Android app
// TODO: create sending form
// TODO: create message listing
// TODO: put a resend button in case of timeout or error
// TODO: setup a daily cron job for sending messages toi the next day but just on monday to saturday
// TODO: in case message stays on pending, then run a cronjob to cleanup and set the status to error with message timeout
