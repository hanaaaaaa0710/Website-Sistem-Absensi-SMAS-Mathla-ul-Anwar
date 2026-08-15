<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $absensi_id
 * @property int $siswa_id
 * @property string|null $catatan
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AbsensiHarian $absensi
 * @property-read \App\Models\User $creator
 * @property-read \App\Models\Siswa $siswa
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi whereAbsensiId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi whereSiswaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CatatanAbsensi whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CatatanAbsensi extends Model
{
    protected $table = 'catatan_absensi';
    protected $fillable = ['absensi_id','siswa_id','catatan','created_by'];

    public function absensi()
    {
        return $this->belongsTo(AbsensiHarian::class, 'absensi_id');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}