<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Kupon extends Model
{
    protected $table = 'kupon';

    protected $fillable = [
        'kode',
        'tipe',
        'nilai',
        'min_belanja',
        'max_diskon',
        'batas_penggunaan',
        'jumlah_terpakai',
        'batas_per_pelanggan',
        'mulai_berlaku',
        'berakhir',
        'aktif',
        'deskripsi'
    ];

    protected function casts(): array
    {
        return [
            'nilai' => 'decimal:2',
            'min_belanja' => 'decimal:2',
            'max_diskon' => 'decimal:2',
            'mulai_berlaku' => 'date',
            'berakhir' => 'date',
            'aktif' => 'boolean',
        ];
    }

    // Relationships
    public function penggunaan()
    {
        return $this->hasMany(PenggunaanKupon::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    // Helper Methods
    public function hitungDiskon($subtotal)
    {
        if ($this->tipe === 'fixed') {
            return min((float) $this->nilai, $subtotal);
        }

        // Percent
        $diskon = ($subtotal * (float) $this->nilai) / 100;

        if ($this->max_diskon) {
            return min($diskon, (float) $this->max_diskon);
        }

        return $diskon;
    }

    public function isValid()
    {
        $now = Carbon::now();

        return $this->aktif
            && $now->between($this->mulai_berlaku, $this->berakhir)
            && ($this->batas_penggunaan === null || $this->jumlah_terpakai < $this->batas_penggunaan);
    }

    public function bisaDigunakanOleh($pelangganId)
    {
        $jumlahDigunakan = $this->penggunaan()
            ->where('pelanggan_id', $pelangganId)
            ->count();

        return $jumlahDigunakan < $this->batas_per_pelanggan;
    }

    // Scopes
    public function scopeAktif($query)
    {
        $now = Carbon::now();
        return $query->where('aktif', true)
            ->whereDate('mulai_berlaku', '<=', $now)
            ->whereDate('berakhir', '>=', $now);
    }
}
