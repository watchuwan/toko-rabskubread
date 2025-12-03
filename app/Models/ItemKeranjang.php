<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemKeranjang extends Model
{
    protected $table = 'item_keranjang';

    protected $fillable = [
        'keranjang_id',
        'produk_id',
        'jumlah',
        'harga',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'harga' => 'decimal:2',
        ];
    }

    // Relationships
    public function keranjang()
    {
        return $this->belongsTo(Keranjang::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Helper Methods
    public function subtotal()
    {
        return $this->harga * $this->jumlah;
    }
}
