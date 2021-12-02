<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_options', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url')->default('#');
            $table->string('icon')->nullable();
            $table->enum('type', ['link', 'item'])->default('item');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('menu_options',function (Blueprint $table){

            $table->unsignedBigInteger('menu_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();

            $table->foreign('menu_id')->references('id')->on('menus');
            $table->foreign('parent_id')->references('id')->on('menu_options')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_options');
    }
}
