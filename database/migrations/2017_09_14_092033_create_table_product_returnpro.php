<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableProductReturnpro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_returnpro', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('returnpro_id');
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
        Schema::dropIfExists('product_returnpro');
    }
}
