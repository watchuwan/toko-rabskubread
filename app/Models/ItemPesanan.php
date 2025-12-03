<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    protected $table = 'item_pesanan';

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'nama_produk',
        'jumlah',
        'harga',
        'harga_asli',
        'diskon_item',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'harga' => 'decimal:2',
            'harga_asli' => 'decimal:2',
            'diskon_item' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    // Relationships
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
