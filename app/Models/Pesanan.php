<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    protected $fillable = [
        'pelanggan_id',
        'alamat_id',
        'kupon_id',
        'nomor_pesanan',
        'subtotal',
        'diskon_kupon',
        'diskon_produk',
        'biaya_ongkir',
        'total_bayar',
        'status',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'diskon_kupon' => 'decimal:2',
            'diskon_produk' => 'decimal:2',
            'biaya_ongkir' => 'decimal:2',
            'total_bayar' => 'decimal:2',
        ];
    }

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function alamat()
    {
        return $this->belongsTo(Alamat::class);
    }

    public function kupon()
    {
        return $this->belongsTo(Kupon::class);
    }

    public function items()
    {
        return $this->hasMany(ItemPesanan::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helper Methods
    public static function generateNomorPesanan()
    {
        $prefix = 'ORD';
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -6));
        return "{$prefix}-{$date}-{$random}";
    }
}
