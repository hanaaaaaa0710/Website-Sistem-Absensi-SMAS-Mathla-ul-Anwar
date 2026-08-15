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
    Schema::table('siswa', function (Blueprint $table) {
        if (!Schema::hasColumn('siswa', 'orang_tua_id')) {
            $table->foreignId('orang_tua_id')->nullable()->constrained('orang_tua')->nullOnDelete();
        }
    });
}

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            if (Schema::hasColumn('siswa', 'orang_tua_id')) {
                $table->dropConstrainedForeignId('orang_tua_id');
            }
        });
    }
    /**
     * Reverse the migrations.
     */
    
};
