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
            $table->integer('id_tbl_pelanggan');
            $table->string('no_pelanggan')->unique();
            $table->string('no_kwh_meter')->nullable()->unique();
            $table->integer('id_tarif');
            $table->integer('id_witel');
            $table->integer('id_daya');
            $table->integer('id_pic');
            $table->integer('id_biaya_admin');
            $table->boolean('is_active')->default(1);
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