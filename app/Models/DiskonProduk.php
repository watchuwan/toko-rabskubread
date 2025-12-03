<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiskonProduk extends Model
{
    protected $table = 'diskon_produk';

    protected $fillable = [
        'produk_id',
        'tipe',
        'nilai',
        'mulai_berlaku',
        'berakhir',
        'aktif',
        'label'
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
            'mulai_berlaku' => 'date',
            'berakhir' => 'date',
            'aktif' => 'boolean',
        ];
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function hitungHargaDiskon($hargaAsli)
    {
        if ($this->tipe === 'fixed') {
            return max(0, $hargaAsli - (float) $this->nilai);
        }

        return $hargaAsli - (($hargaAsli * (float) $this->nilai) / 100);
    }

    public function scopeAktif($query)
    {
        $now = Carbon::now();
        return $query->where('aktif', true)
            ->whereDate('mulai_berlaku', '<=', $now)
            ->whereDate('berakhir', '>=', $now);
    }
}
