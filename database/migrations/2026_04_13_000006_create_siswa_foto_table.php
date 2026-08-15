<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_foto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->onDelete('cascade');

            $table->string('foto_referensi')
                ->comment('Path ke file foto');

            $table->string('deskripsi')
                ->nullable()
                ->comment('Foto depan, samping, dll');

            $table->enum('kualitas_scan', ['Baik', 'Cukup', 'Kurang'])
                ->default('Cukup');

            $table->timestamps();
            $table->index('siswa_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_foto');
    }
};