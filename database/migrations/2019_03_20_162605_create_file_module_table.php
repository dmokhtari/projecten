<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileModuleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_module', function (Blueprint $table) {
            $table->bigInteger('file_id')->unsigned();
            $table->bigInteger('module_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('file_module', function($table) {
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');;
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
        Schema::dropIfExists('file_module');
    }
}
