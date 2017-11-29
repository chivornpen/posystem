<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTransections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('brand_id')->nullable();
            $table->integer('batchID');
            $table->date('transectionDate');
            $table->string('transectionCode');
            $table->integer('chartaccount_id');
            $table->integer('typeaccount_id');
            $table->double('drAmt');
            $table->double('crAmt');
            $table->double('runningBalance');
            $table->double('Postamount');
            $table->string('currency');
            $table->float('exchangeRate');
            $table->integer('user_id');
            $table->string('recordstatus')->nullable();
            $table->integer('recordnumber')->nullable();
            $table->integer('authorize_id')->nullable();
            $table->date('authorize_date')->nullable();
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
        Schema::dropIfExists('transections');
    }
}
