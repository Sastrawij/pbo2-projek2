<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Servis extends Model
{
    protected $table = 'servis';

    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public const STATUS = [
        'menunggu'   => 'Menunggu',
        'dikerjakan' => 'Dikerjakan',
        'selesai'    => 'Selesai',
        'diambil'    => 'Diambil',
    ];

    public const BADGE = [
        'menunggu'   => 'warning',
        'dikerjakan' => 'info',
        'selesai'    => 'success',
        'diambil'    => 'secondary',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class, 'kendaraan_id');
    }

    public function mekanik(): BelongsTo
    {
        return $this->belongsTo(Mekanik::class, 'mekanik_id');
    }

    public function layanan(): BelongsToMany
    {
        return $this->belongsToMany(Layanan::class, 'servis_layanan', 'servis_id', 'layanan_id')
            ->withPivot('harga');
    }

    public function sukuCadang(): BelongsToMany
    {
        return $this->belongsToMany(SukuCadang::class, 'servis_suku_cadang', 'servis_id', 'suku_cadang_id')
            ->withPivot('jumlah', 'harga');
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS[$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return self::BADGE[$this->status] ?? 'secondary';
    }

    /** Nomor servis otomatis, contoh: SRV-20261010-001 */
    public static function buatNomor(): string
    {
        $prefix = 'SRV-' . now()->format('Ymd') . '-';
        $urut = static::where('no_servis', 'like', $prefix . '%')->count() + 1;

        do {
            $nomor = $prefix . str_pad((string) $urut, 3, '0', STR_PAD_LEFT);
            $urut++;
        } while (static::where('no_servis', $nomor)->exists());

        return $nomor;
    }
}
