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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_penghuni_id')->constrained()->cascadeOnDelete();
            $table->string('invoice'); // PDF/gambar tagihan dari admin
            $table->string('bukti_transfer'); // bukti pembayaran dari penghuni
            $table->date('tgl_bayar');
            $table->enum('status_pembayaran', ['proses', 'lunas', 'ditolak'])->default('proses');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
