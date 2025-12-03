<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GambarProduk extends Model
{
    protected $table = 'gambar_produk';

    protected $fillable = [
        'produk_id',
        'path_gambar',
        'gambar_utama',
        'urutan',
    ];

    protected function casts(): array
    {
        return [
            'gambar_utama' => 'boolean',
            'urutan' => 'integer',
        ];
    }

    // Relationships
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
