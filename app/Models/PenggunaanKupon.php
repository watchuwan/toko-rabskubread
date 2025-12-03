<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaanKupon extends Model
{
    protected $table = 'penggunaan_kupon';

    protected $fillable = [
        'kupon_id',
        'pelanggan_id',
        'pesanan_id',
        'nilai_diskon'
    ];

    protected function casts(): array
    {
        return [
            'nilai_diskon' => 'decimal:2',
        ];
    }

    public function kupon()
    {
        return $this->belongsTo(Kupon::class);
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}
