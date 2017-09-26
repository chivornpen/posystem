<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductReturnprosd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_returnprosd', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('returnprosd_id');
            $table->integer('product_id');
            $table->integer('qtyreturn');
            $table->integer('qtyorder');
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
        Schema::dropIfExists('product_returnprosd');
    }
}
