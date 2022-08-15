<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prabayars', function (Blueprint $table) {
            $table->id();
            $table->integer('id_no_kwh_meter');
            $table->bigInteger('nominal_pembelian_token');
            $table->bigInteger('token');
            $table->text('keterangan'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prabayars');
    }
};