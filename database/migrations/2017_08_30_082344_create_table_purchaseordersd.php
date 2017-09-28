<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePurchaseordersd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchaseordersd', function (Blueprint $table) {
            $table->increments('id');
            $table->date('poDate')->nullable();
            $table->date('dueDate')->nullable();
            $table->double('totalAmount');
            $table->integer('discount')->nullable();
            $table->integer('user_id');
            $table->integer('customer_id')->nullable();
            $table->integer('cod')->nullable();
            $table->double('grandTotal')->nullable();
            $table->tinyInteger('isGenerate')->nullable();
            $table->tinyInteger('isDelivery')->nullable();
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
        Schema::dropIfExists('purchaseordersd');
    }
}
