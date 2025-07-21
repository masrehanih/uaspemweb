<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    protected $fillable = [
        'pasien_id',
        'layanan_id',
        'jumlah',
        'total_harga',
        'tanggal',
    ];

    // Relasi ke Pasien
    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }

    // Relasi ke Layanan
    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    // Event untuk hitung total harga otomatis
    protected static function booted(): void
    {
        static::creating(function ($transaksi) {
            if (!$transaksi->total_harga && $transaksi->layanan && $transaksi->jumlah) {
                $transaksi->total_harga = $transaksi->layanan->harga * $transaksi->jumlah;
            }
        });

        static::updating(function ($transaksi) {
            if ($transaksi->isDirty(['jumlah', 'layanan_id'])) {
                $transaksi->total_harga = $transaksi->layanan->harga * $transaksi->jumlah;
            }
        });
    }
}
