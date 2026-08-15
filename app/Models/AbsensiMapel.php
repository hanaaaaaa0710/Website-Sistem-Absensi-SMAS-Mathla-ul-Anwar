<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $jadwal_pelajaran_id
 * @property int $siswa_id
 * @property \Illuminate\Support\Carbon $tanggal
 * @property string|null $jam_masuk
 * @property string $status
 * @property string|null $keterangan
 * @property int|null $scan_score
 * @property string|null $catatan
 * @property int $dicatat_oleh
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Guru $dicatatOleh
 * @property-read \App\Models\JadwalPelajaran $jadwalPelajaran
 * @property-read \App\Models\Siswa $siswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereDicatatOleh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereJadwalPelajaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereJamMasuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereScanScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereTanggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AbsensiMapel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class AbsensiMapel extends Model
{
    protected $table = 'absensi_mapel';

    protected $fillable = [
        'jadwal_pelajaran_id',
        'siswa_id',
        'tanggal',
        'jam_masuk',
        'status',
        'keterangan',
        'catatan',
        'scan_score',
        'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function jadwalPelajaran(): BelongsTo
    {
        return $this->belongsTo(JadwalPelajaran::class, 'jadwal_pelajaran_id');
    }

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function dicatatOleh(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'dicatat_oleh');
    }
}