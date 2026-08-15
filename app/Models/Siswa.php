<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Kelas;
use App\Models\AbsensiHarian;
use App\Models\AbsensiMapel;

/**
 * @property int $id
 * @property int|null $user_id
 * @property int|null $kelas_id
 * @property string|null $nis
 * @property string $nama_siswa
 * @property string $jenis_kelamin
 * @property string $ttl
 * @property string|null $alamat
 * @property string|null $no_hp
 * @property string|null $nama_ortu
 * @property string|null $no_hp_ortu
 * @property string|null $foto
 * @property string $status
 * @property string|null $tahun_ajaran
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AbsensiHarian> $absensiHarian
 * @property-read int|null $absensi_harian_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, AbsensiMapel> $absensiMapel
 * @property-read int|null $absensi_mapel_count
 * @property-read Kelas|null $kelas
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SiswaFoto> $siswaFoto
 * @property-read int|null $siswa_foto_count
 * @property-read User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa kelas($kelasId)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereKelasId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNamaOrtu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNamaSiswa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNis($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereNoHpOrtu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereTahunAjaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Siswa whereUserId($value)
 * @mixin \Eloquent
 */
class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nama_siswa',
        'jenis_kelamin',
        'ttl',
        'alamat',
        'no_hp',
        'nama_ortu',
        'no_hp_ortu',
        'hubungan_ortu',
        'status',
        'tahun_ajaran',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function absensiHarian(): HasMany
    {
        return $this->hasMany(AbsensiHarian::class, 'siswa_id');
    }

    public function absensiMapel(): HasMany
    {
        return $this->hasMany(AbsensiMapel::class, 'siswa_id');
    }

    public function siswaFoto(): HasMany
    {
        return $this->hasMany(SiswaFoto::class);
    }

    // Methods
    public function getKehadiranBulanIni()
    {
        $bulan = now()->month;
        $tahun = now()->year;

        return $this->absensiHarian()
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->get();
    }

    public function getPersentaseKehadiran()
    {
        $hari_kerja = $this->absensiHarian()
            ->whereMonth('tanggal', now()->month)
            ->count();

        $hadir = $this->absensiHarian()
            ->whereMonth('tanggal', now()->month)
            ->where('status', 'Hadir')
            ->count();

        if ($hari_kerja == 0) return 0;
        return round(($hadir / $hari_kerja) * 100, 2);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }

    public function scopeKelas($query, $kelasId)
    {
        return $query->where('kelas_id', $kelasId);
    }

}