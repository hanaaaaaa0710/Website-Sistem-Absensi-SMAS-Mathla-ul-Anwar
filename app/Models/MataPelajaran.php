<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $kode_mapel
 * @property string $nama_mapel
 * @property string|null $deskripsi
 * @property int $sks Satuan Kredit Semester
 * @property bool $is_aktif
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalPelajaran> $jadwalPelajaran
 * @property-read int|null $jadwal_pelajaran_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereIsAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereKodeMapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereNamaMapel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereSks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MataPelajaran whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'deskripsi',
        'sks',
        'is_aktif',
    ];

    protected $casts = [
        'is_aktif' => 'boolean',
    ];

    // Relationships
    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('is_aktif', true);
    }
}
