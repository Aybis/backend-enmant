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
            $table->unsignedBigInteger('id_no_kwh_meter');
            $table->foreign('id_no_kwh_meter')->references('id')->on('kwh_meters')->onDelete('cascade');
            $table->bigInteger('nominal_pembelian_token');
            $table->string('token');
            $table->bigInteger('biaya_admin')->nullable();
            $table->text('keterangan')->nullable();
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
