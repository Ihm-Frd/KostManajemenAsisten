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
        Schema::create('data_penghunis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('data_kamar_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nama');
            $table->string('nik')->required()->unique();
            $table->date('tgl_lahir')->required();
            $table->string('no_wa')->required();
            $table->string('jns_kelamin')->required();
            $table->string('status')->required();
            $table->string('pas_foto')->required();
            $table->string('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_penghunis');
    }
};
