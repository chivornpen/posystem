<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableStockoutreImport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_stockoutre', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('stockoutre_id');
            $table->integer('import_id');
            $table->integer('product_id');
            $table->integer('qty');
            $table->date('expd');
            $table->integer('qty')->nullable();
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
        Schema::dropIfExists('import_stockoutre');
    }
}
