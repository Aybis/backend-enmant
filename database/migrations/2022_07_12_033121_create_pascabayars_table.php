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

            // Relation
            $table->unsignedBigInteger('id_no_kwh_meter');
            $table->foreign('id_no_kwh_meter')->references('id')->on('kwh_meters')->onDelete('cascade');
            $table->unsignedBigInteger('id_pic')->nullable();
            $table->foreign('id_pic')->references('id')->on('pics')->onDelete('cascade');
            $table->unsignedBigInteger('id_biaya_admin')->nullable();
            $table->foreign('id_biaya_admin')->references('id')->on('biaya_admins')->onDelete('cascade');

            // Column
            $table->bigInteger('meter_awal')->nullable();
            $table->bigInteger('meter_akhir')->nullable();
            $table->bigInteger('selisih')->nullable();
            $table->bigInteger('tagihan')->nullable();
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