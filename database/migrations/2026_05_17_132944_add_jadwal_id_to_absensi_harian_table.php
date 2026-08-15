<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            $table->foreignId('jadwal_id')
                  ->nullable()
                  ->constrained('jadwal_pelajaran')
                  ->after('kelas_id'); // letak kolom
        });
    }

    public function down(): void
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            $table->dropForeign(['jadwal_id']);
            $table->dropColumn('jadwal_id');
        });
    }
};