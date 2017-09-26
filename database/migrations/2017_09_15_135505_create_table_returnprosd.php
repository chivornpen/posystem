<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableReturnprosd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returnprosd', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stockoutsd_id');
            $table->integer('purchaseordersd_id')->nullable();
            $table->integer('returnBy');
            $table->char('status',3);
            $table->tinyInteger('isGenerate')->default(0);
            $table->integer('brand_id')->nullable();
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
        Schema::dropIfExists('returnprosd');
    }
}
