<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Kelas;
  

/**
 * @property int $id
 * @property int $user_id
 * @property string $nip Nomor Induk Pegawai
 * @property string $nama_guru
 * @property string $jenis_kelamin
 * @property string $ttl Tempat Tanggal Lahir
 * @property string|null $no_hp
 * @property string|null $alamat
 * @property string|null $gelar_pendidikan
 * @property string|null $bidang_keahlian
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $mata_pelajaran_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AbsensiMapel> $absensiMapel
 * @property-read int|null $absensi_mapel_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\JadwalPelajaran> $jadwalPelajaran
 * @property-read int|null $jadwal_pelajaran_count
 * @property-read int|null $kelas_yang_diwalai_count
 * @property-read \App\Models\MataPelajaran|null $mataPelajaran
 * @property-read User $user
 * @property-read int|null $wali_kelas_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru aktif()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereBidangKeahlian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereGelarPendidikan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereMataPelajaranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNamaGuru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereNoHp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereTtl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Guru whereUserId($value)
 * @mixin \Eloquent
 */
class Guru extends Model
{
    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'nip',
        'nama_guru',
        'jenis_kelamin',
        'ttl',
        'no_hp',
        'alamat',
        'gelar_pendidikan',
        'bidang_keahlian',
        'status',
        'mata_pelajaran_id',
        'kelas_wali_id',
        'tahun_ajaran_wali',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kelasWali(): BelongsTo
    {
        return $this->belongsTo(Kelas::class, 'kelas_wali_id');
    }

    public function jadwalPelajaran(): HasMany
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    public function absensiMapel(): HasMany
    {
        return $this->hasMany(AbsensiMapel::class, 'dicatat_oleh');
    }

    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class, 'mata_pelajaran_id');
    }
   

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'Aktif');
    }
}