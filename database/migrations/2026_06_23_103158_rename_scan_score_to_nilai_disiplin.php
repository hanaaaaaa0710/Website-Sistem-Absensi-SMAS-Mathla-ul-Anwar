<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            if (Schema::hasColumn('absensi_harian', 'scan_score')) {
                $table->renameColumn('scan_score', 'nilai_disiplin');
            }
        });

        Schema::table('absensi_mapel', function (Blueprint $table) {
            if (Schema::hasColumn('absensi_mapel', 'scan_score')) {
                $table->renameColumn('scan_score', 'nilai_disiplin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            if (Schema::hasColumn('absensi_harian', 'nilai_disiplin')) {
                $table->renameColumn('nilai_disiplin', 'scan_score');
            }
        });

        Schema::table('absensi_mapel', function (Blueprint $table) {
            if (Schema::hasColumn('absensi_mapel', 'nilai_disiplin')) {
                $table->renameColumn('nilai_disiplin', 'scan_score');
            }
        });
    }
};