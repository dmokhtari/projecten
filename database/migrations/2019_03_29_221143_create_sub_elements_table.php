<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_elements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('element_id')->unsigned();
            $table->string('type');
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->longText('description')->nullable();
            $table->binary('binary')->nullable();
            $table->timestamps();
        });

        Schema::table('sub_elements', function($table) {
            $table->foreign('element_id')->references('id')->on('elements')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_elements');
    }
}
