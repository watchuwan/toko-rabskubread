<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'pesanan_id',
        'metode_pembayaran_id',
        'nomor_pembayaran',
        'jumlah',
        'status',
        'id_transaksi_midtrans',
        'snap_token',
        'respon_midtrans',
        'dibayar_pada',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'decimal:2',
            'dibayar_pada' => 'datetime',
        ];
    }

    // Relationships
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(MetodePembayaran::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helper Methods
    public static function generateNomorPembayaran()
    {
        $prefix = 'PAY';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        return "{$prefix}-{$date}-{$random}";
    }
}
