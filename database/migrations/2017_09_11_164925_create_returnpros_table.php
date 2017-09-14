<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReturnprosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('returnpros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stockout_id');
            $table->integer('purchaseorder_id')->nullable();
            $table->integer('returnBy');
            $table->char('status',3);
            $table->tinyInteger('isGenerate')->default(0);
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
        Schema::dropIfExists('returnpros');
    }
}
