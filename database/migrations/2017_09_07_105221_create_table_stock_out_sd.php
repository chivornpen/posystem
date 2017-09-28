<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStockOutSd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stockoutsd', function (Blueprint $table) {
            $table->increments('id');
            $table->date('stockoutDate');
            $table->integer('purchaseordersd_id');
            $table->integer('user_id');
            $table->integer('brand_id')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('stockoutsd');
    }
}
