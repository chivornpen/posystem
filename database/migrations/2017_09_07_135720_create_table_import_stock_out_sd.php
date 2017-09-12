<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableImportStockOutSd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_stockoutsd', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stockoutsd_id');
            $table->integer('subimport_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->date('expd');
            $table->tinyInteger('status')->nullable();
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
        Schema::dropIfExists('import_stockoutsd');
    }
}
