<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            if (!Schema::hasColumn('notifikasi', 'wa_nomor')) {
                $table->string('wa_nomor', 25)
                    ->nullable()
                    ->after('sudah_dibaca');
            }

            if (!Schema::hasColumn('notifikasi', 'wa_status')) {
                $table->enum('wa_status', [
                    'Menunggu',
                    'Dibuka',
                    'Terkirim',
                    'Nomor Tidak Tersedia',
                ])->nullable()->after('wa_nomor');
            }

            if (!Schema::hasColumn('notifikasi', 'wa_dibuka_at')) {
                $table->timestamp('wa_dibuka_at')
                    ->nullable()
                    ->after('wa_status');
            }

            if (!Schema::hasColumn('notifikasi', 'wa_terkirim_at')) {
                $table->timestamp('wa_terkirim_at')
                    ->nullable()
                    ->after('wa_dibuka_at');
            }

            if (!Schema::hasColumn('notifikasi', 'wa_dikonfirmasi_oleh')) {
                $table->foreignId('wa_dikonfirmasi_oleh')
                    ->nullable()
                    ->after('wa_terkirim_at')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifikasi', function (Blueprint $table) {
            if (Schema::hasColumn('notifikasi', 'wa_dikonfirmasi_oleh')) {
                $table->dropForeign(['wa_dikonfirmasi_oleh']);
            }

            $columns = [
                'wa_nomor',
                'wa_status',
                'wa_dibuka_at',
                'wa_terkirim_at',
                'wa_dikonfirmasi_oleh',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('notifikasi', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};