<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $siswa_id
 * @property string $foto_referensi Path ke file foto
 * @property string|null $deskripsi Foto depan, samping, dll
 * @property string $kualitas_scan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Siswa $siswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto whereDeskripsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto whereFotoReferensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto whereKualitasScan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SiswaFoto whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SiswaFoto extends Model
{
    protected $table = 'siswa_foto';

    protected $fillable = [
        'siswa_id',
        'foto_referensi',
        'deskripsi',
        'kualitas_scan',
    ];

    // Relationships
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }
}