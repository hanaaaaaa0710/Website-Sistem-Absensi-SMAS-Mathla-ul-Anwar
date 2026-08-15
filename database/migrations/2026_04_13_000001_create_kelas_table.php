<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelas')
                ->unique()
                ->comment('Format: 10-A, 11-B, 12-C');

            $table->string('nama_kelas');
            $table->integer('tingkat')
                ->comment('10, 11, 12');

            $table->string('jurusan')
                ->nullable()
                ->comment('IPA, IPS, Bahasa');

            $table->integer('kapasitas')
                ->default(40);

            $table->string('tahun_ajaran')
                ->comment('2025/2026');

            $table->timestamps();
            $table->index(['tingkat', 'tahun_ajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};