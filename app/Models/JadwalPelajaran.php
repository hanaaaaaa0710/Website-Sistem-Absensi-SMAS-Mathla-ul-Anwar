<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $kelas_id
 * @property int $guru_id
 * @property int $mata_pelajaran_id
 * @property string $hari
 * @property string $jam_mulai
 * @property string $jam_selesai
 * @property string|null $ruang_kelas
 * @property int $semester
 * @property string $tahun_ajaran
 * @property bool $is_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AbsensiMapel> $absensiMapel
 * @property-read int|null $absensi_mapel_count
 * @property-read \App\Models\Guru $guru
 * @property-read \App\Models\Kelas $kelas
 * @property-read \App\Models\MataPelajaran $mataPelajaran
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran hari($hari)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran tahunAjaran($tahun)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereGuruId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereHari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereIsAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereJamMulai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereJamSelesai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereMataPelajaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereRuangKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereSemester($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|JadwalPelajaran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class JadwalPelajaran extends Model
{
    protected $table = 'jadwal_pelajaran';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mata_pelajaran_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruang_kelas',
        'semester',
        'tahun_ajaran',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    // Relationships
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function absensiMapel(): HasMany
    {
        return $this->hasMany(AbsensiMapel::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }

    public function scopeHari($query, $hari)
    {
        return $query->where('hari', $hari);
    }

    public function scopeTahunAjaran($query, $tahun)
    {
        return $query->where('tahun_ajaran', $tahun);
    }
}