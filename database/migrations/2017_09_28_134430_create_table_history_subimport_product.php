<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHistorySubimportProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subimport_producthistory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subimport_id');
            $table->integer('brand_id')->nullable();
            $table->integer('product_id');
            $table->integer('qty');
            $table->date('mfd');
            $table->date('expd');
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
        Schema::dropIfExists('subimport_producthistory');
    }
}
