<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_mapel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pelajaran_id')
                ->constrained('jadwal_pelajaran')
                ->onDelete('cascade');

            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->onDelete('cascade');

            $table->date('tanggal');
            $table->time('jam_masuk')
                ->nullable();

            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpha', 'Terlambat'])
                ->default('Hadir');

            $table->text('keterangan')
                ->nullable();

            $table->foreignId('dicatat_oleh')
                ->constrained('guru')
                ->onDelete('cascade')
                ->comment('Guru yang mencatat');

            $table->timestamps();
            $table->index(['jadwal_pelajaran_id', 'tanggal']);
            $table->index(['siswa_id', 'tanggal']);

            // Seorang siswa hanya boleh punya 1 record per jadwal per tanggal
            $table->unique(['jadwal_pelajaran_id', 'siswa_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_mapel');
    }
};