<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIconSubElementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('icon_sub_element', function (Blueprint $table) {
            $table->integer('icon_id')->unsigned();
            $table->integer('sub_element_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('icon_sub_element', function($table) {
            $table->foreign('icon_id')->references('id')->on('icons')->onDelete('cascade');
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
        Schema::dropIfExists('icon_sub_element');
    }
}
