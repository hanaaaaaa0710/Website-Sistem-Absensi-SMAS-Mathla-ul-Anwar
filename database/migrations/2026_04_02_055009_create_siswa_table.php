<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('kelas_id')->nullable();

            $table->string('nis')->unique()->nullable();
            $table->string('nama_siswa');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('ttl');
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('nama_ortu')->nullable();
            $table->string('no_hp_ortu')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');
            $table->string('tahun_ajaran')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};