<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('data_kamars', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kamar')->required();
            $table->string('lokasi')->required();
            $table->string('harga_bulanan')->required();
            $table->string('fasilitas')->required();
            $table->string('status_kamar')->default('Kosong');
            $table->string('keterangan')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kamars');
    }
};
