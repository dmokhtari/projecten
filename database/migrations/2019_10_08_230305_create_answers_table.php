<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('file_id')->unsigned();
            $table->bigInteger('module_id')->unsigned();
            $table->bigInteger('element_id')->unsigned();
            $table->bigInteger('sub_element_id')->unsigned();
            $table->binary('answer');
            $table->timestamps();
        });

        Schema::table('answers', function($table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('element_id')->references('id')->on('elements')->onDelete('cascade');
            $table->foreign('sub_element_id')->references('id')->on('sub_elements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('answers');
    }
}
