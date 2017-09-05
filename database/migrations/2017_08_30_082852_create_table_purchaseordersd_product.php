<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePurchaseordersdProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseordersd_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purchaseordersd_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->double('unitPrice');
            $table->double('amount');
            $table->integer('user_id');
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
        Schema::dropIfExists('purchaseordersd_product');
    }
}
