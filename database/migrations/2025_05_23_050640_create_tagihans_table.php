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
        Schema::create('tagihans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_penghuni_id')->constrained()->cascadeOnDelete();
            $table->string('periode'); // misal: 2025-06-01 (bulan tagihan)
            $table->string('nominal');
            $table->string('status')->default('belum_dibayar');
            $table->date('jatuh_tempo');
            $table->text('catatan')->nullable();
            $table->unique(['data_penghuni_id', 'periode', 'jatuh_tempo']);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihans');
    }
};
