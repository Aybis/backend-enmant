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
        Schema::create('dendas', function (Blueprint $table) {
            $table->id();
            // Relation
            $table->unsignedBigInteger('id_no_kwh_meter');
            $table->foreign('id_no_kwh_meter')->references('id')->on('kwh_meters')->onDelete('cascade');
            // Column
            $table->string('no_reference')->unique()->nullable();
            $table->bigInteger('nominal')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('dendas');
    }
};
