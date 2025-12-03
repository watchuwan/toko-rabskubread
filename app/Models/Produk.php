<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';

    protected $fillable = [
        'kategori_id',
        'nama',
        'slug',
        'deskripsi',
        'harga',
        'stok',
        'sku',
        'aktif',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'stok' => 'integer',
            'aktif' => 'boolean',
        ];
    }

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function gambar()
    {
        return $this->hasMany(GambarProduk::class);
    }

    public function gambarUtama()
    {
        return $this->hasOne(GambarProduk::class)->where('gambar_utama', true);
    }

    public function itemKeranjang()
    {
        return $this->hasMany(ItemKeranjang::class);
    }

    public function itemPesanan()
    {
        return $this->hasMany(ItemPesanan::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeTersedia($query)
    {
        return $query->where('aktif', true)->where('stok', '>', 0);
    }
}
