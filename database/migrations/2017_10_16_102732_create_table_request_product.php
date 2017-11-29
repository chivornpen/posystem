<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRequestProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestpro', function (Blueprint $table) {
            $table->increments('id');
            $table->date('reqDate');
            $table->integer('reqby');
            $table->integer('user_id');
            $table->string('status')->nullable();
            $table->string('returntype')->nullable();
            $table->integer('recordnum')->nullable();
            $table->integer('auth_id')->nullable();
            $table->date('auth_date')->nullable();
            $table->tinyInteger('is_export')->nullable();
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
        Schema::dropIfExists('requestpro');
    }
}
