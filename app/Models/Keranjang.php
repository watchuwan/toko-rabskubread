<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $table = 'keranjang';

    protected $fillable = [
        'pelanggan_id',
    ];

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function items()
    {
        return $this->hasMany(ItemKeranjang::class);
    }

    // Helper Methods
    public function totalHarga()
    {
        return $this->items->sum(function ($item) {
            return $item->harga * $item->jumlah;
        });
    }

    public function totalItem()
    {
        return $this->items->sum('jumlah');
    }
}
