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
        Schema::create('pascabayars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_no_kwh_meter');
            $table->foreign('id_no_kwh_meter')->references('id')->on('kwh_meters')->onDelete('cascade');
            $table->bigInteger('meter_awal');
            $table->bigInteger('meter_akhir');
            $table->bigInteger('selisih');
            $table->bigInteger('tagihan');
            $table->bigInteger('denda')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('status')->nullable();
            $table->date('tanggal_transaksi')->nullable();
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
        Schema::dropIfExists('pascabayars');
    }
};