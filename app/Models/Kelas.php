<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $kode_kelas Format: 10-A, 11-B, 12-C
 * @property string $nama_kelas
 * @property int $tingkat 10, 11, 12
 * @property string|null $jurusan IPA, IPS, Bahasa
 * @property int $kapasitas
 * @property string $tahun_ajaran 2025/2026
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalPelajaran> $jadwalPelajaran
 * @property-read int|null $jadwal_pelajaran_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Siswa> $siswa
 * @property-read int|null $siswa_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Guru> $waliKelas
 * @property-read int|null $wali_kelas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereJurusan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereKapasitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereKodeKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereNamaKelas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereTingkat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Kelas whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'kode_kelas',
        'nama_kelas',
        'tingkat',
        'jurusan',
        'kapasitas',
        'tahun_ajaran',
    ];

    // Relationships
    public function siswa(): HasMany
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function waliKelas(): HasOne
    {
        return $this->hasOne(Guru::class,'kelas_wali_id');
    }

    // Methods
    public function getJumlahSiswa()
    {
        return $this->siswa()->where('status', 'Aktif')->count();
    }

    public function getWaliKelasAktif()
    {
        return $this->waliKelas()->first();
    }

    public function scopeAktif($query)
    {
        return $query;
    }
    
}