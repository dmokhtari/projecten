<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('module_id')->nullable()->unsigned();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->timestamps();
        });

        Schema::table('elements', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');;
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('elements');
    }
}
