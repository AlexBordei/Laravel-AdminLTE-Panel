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
            $table->unsignedBigInteger('subscription_id');
            $table->dateTime('starting')->nullable();
            $table->dateTime('ending')->nullable();
            $table->enum('status', ['pending', 'scheduled', 'confirmed', 'canceled'])->default('pending');
            $table->string('google_event_id')->nullable();
            $table->timestamps();
        });

        Schema::table('events',function (Blueprint $table){
            $table->foreign('subscription_id')->references('id')->on('subscriptions')->cascadeOnDelete();

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
