<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Produk extends Model
{

    use HasSlug;
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

        public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nama')
            ->saveSlugsTo('slug');
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

    public function diskonAktif()
    {
        return $this->hasOne(DiskonProduk::class)->aktif();
    }

    public function hargaSetelahDiskon()
    {
        $diskon = $this->diskonAktif;

        if (!$diskon) {
            return $this->harga;
        }

        return $diskon->hitungHargaDiskon($this->harga);
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
