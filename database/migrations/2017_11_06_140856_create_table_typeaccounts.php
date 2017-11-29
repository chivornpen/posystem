<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTypeaccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('typeaccounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('typeaccountcode');
            $table->string('description')->nullable();
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
        Schema::dropIfExists('typeaccounts');
    }
}
