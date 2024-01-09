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

            // Relation
            $table->unsignedBigInteger('id_no_kwh_meter');
            $table->foreign('id_no_kwh_meter')->references('id')->on('kwh_meters')->onDelete('cascade');
            $table->unsignedBigInteger('id_pic')->nullable();
            $table->foreign('id_pic')->references('id')->on('pics')->onDelete('cascade');
            $table->unsignedBigInteger('id_biaya_admin')->nullable();
            $table->foreign('id_biaya_admin')->references('id')->on('biaya_admins')->onDelete('cascade');

            // Column
            $table->bigInteger('nominal_pembelian_token');
            $table->double('nilai_kwh');
            $table->string('file');
            $table->string('token');
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
