<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $siswa_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string|null $jam_masuk
 * @property string $metode_absensi
 * @property string $status
 * @property string|null $keterangan
 * @property \Illuminate\Database\Eloquent\Collection<int, \App\Models\CatatanAbsensi> $catatan
 * @property int|null $scan_score
 * @property string|null $bukti_izin
 * @property int|null $created_by
 * @property string $status_notifikasi
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $jadwal_id
 * @property-read int|null $catatan_count
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\Siswa $siswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereBuktiIzin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereJadwalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereMetodeAbsensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereScanScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereStatusNotifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiHarian whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AbsensiHarian extends Model
{
    protected $table = 'absensi_harian';

    protected $fillable = [
        'siswa_id',
        'tanggal',
        'status',
        'terlambat',
        'jam_masuk',
        'keterangan',
        'catatan',
        'scan_score',
        'created_by',
        'jadwal_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'terlambat' => 'boolean',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function catatan()
    {
        return $this->hasMany(CatatanAbsensi::class, 'absensi_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}