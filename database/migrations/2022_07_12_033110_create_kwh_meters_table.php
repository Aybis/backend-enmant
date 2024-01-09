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
        Schema::create('kwh_meters', function (Blueprint $table) {
            $table->id();
            $table->string('no_pelanggan')->unique();
            $table->string('no_kwh_meter')->nullable()->unique();

            // Relation
            $table->unsignedBigInteger('id_tbl_pelanggan');
            $table->foreign('id_tbl_pelanggan')->references('id')->on('pelanggans')->onDelete('cascade');
            $table->unsignedBigInteger('id_tarif')->nullable();
            $table->foreign('id_tarif')->references('id')->on('tarifs')->onDelete('cascade');
            $table->unsignedBigInteger('id_witel')->nullable();
            $table->foreign('id_witel')->references('id')->on('witels')->onDelete('cascade');
            $table->unsignedBigInteger('id_daya')->nullable();
            $table->foreign('id_daya')->references('id')->on('dayas')->onDelete('cascade');

            // Column
            $table->boolean('is_active')->default(1);
            $table->bigInteger('bongkar_rampung')->nullable();
            $table->integer('pasang_baru')->nullable();
            $table->boolean('is_prabayar')->default(0)->nullable();
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
        Schema::dropIfExists('kwh_meters');
    }
};
