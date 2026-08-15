<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            $table->text('catatan')->nullable()->after('keterangan');
            $table->integer('scan_score')->nullable()->after('catatan');
        });
    }

    public function down()
    {
        Schema::table('absensi_harian', function (Blueprint $table) {
            $table->dropColumn(['catatan','scan_score']);
        });
    }
};