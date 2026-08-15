<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

/**
 * @property int $id
 * @property int $user_id
 * @property string $judul
 * @property string $isi
 * @property string $tipe
 * @property bool $sudah_dibaca
 * @property \Illuminate\Support\Carbon|null $dibaca_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereDibacaAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereIsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereJudul($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereSudahDibaca($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereTipe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Notifikasi whereUserId($value)
 * @mixin \Eloquent
 */
class Notifikasi extends Model
{
    protected $table = 'notifikasi';

    protected $fillable = [
        'user_id',
        'judul',
        'isi',
        'tipe',
        'sudah_dibaca',
        'dibaca_at',
        'wa_nomor',
        'wa_status',
        'wa_dibuka_at',
        'wa_terkirim_at',
        'wa_dikonfirmasi_oleh',
    ];

    protected $casts = [
        'sudah_dibaca' => 'boolean',
        'dibaca_at' => 'datetime',
        'wa_dibuka_at' => 'datetime',
        'wa_terkirim_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function markAsRead()
    {
        $this->update([
            'sudah_dibaca' => true,
            'dibaca_at' => now(),
        ]);
    }
}