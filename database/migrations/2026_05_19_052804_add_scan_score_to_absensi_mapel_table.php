<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_mapel', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_mapel', 'scan_score')) {
                $table->integer('scan_score')->nullable()->after('keterangan');
            }

            if (!Schema::hasColumn('absensi_mapel', 'catatan')) {
                $table->text('catatan')->nullable()->after('scan_score');
            }
        });
    }

    public function down(): void
    {
        Schema::table('absensi_mapel', function (Blueprint $table) {
            if (Schema::hasColumn('absensi_mapel', 'catatan')) {
                $table->dropColumn('catatan');
            }

            if (Schema::hasColumn('absensi_mapel', 'scan_score')) {
                $table->dropColumn('scan_score');
            }
        });
    }
};