<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('subscription_type_id');
            $table->dateTime('starting');
            $table->dateTime('ending');
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->enum('status', ['active', 'canceled', 'expired', 'pending'])->default('pending');
            $table->timestamps();
        });

        Schema::table('subscriptions',function (Blueprint $table){
            $table->foreign('student_id')->references('id')->on('students');
            $table->foreign('subscription_type_id')->references('id')->on('subscription_types');
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
