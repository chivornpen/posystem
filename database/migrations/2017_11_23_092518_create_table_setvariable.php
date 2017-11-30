<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSetvariable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setvariables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('objectName');
            $table->integer('value');
            $table->string('stringValue')->nullable();
            $table->date('dateValue')->nullable();
            $table->integer('drsign')->nullable();
            $table->integer('crsign')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('variable')->nullable();
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
        Schema::dropIfExists('setvariables');
    }
}
