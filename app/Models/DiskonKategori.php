<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DiskonKategori extends Model
{
    protected $table = 'diskon_kategori';

    protected $fillable = [
        'kategori_id',
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

    // Relationships
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Scopes
    public function scopeAktif($query)
    {
        $now = Carbon::now();
        return $query->where('aktif', true)
            ->whereDate('mulai_berlaku', '<=', $now)
            ->whereDate('berakhir', '>=', $now);
    }

    // Helper Methods
    public function hitungHargaDiskon($hargaAsli)
    {
        if ($this->tipe === 'fixed') {
            return max(0, $hargaAsli - (float) $this->nilai);
        }

        // Percent
        $diskon = ($hargaAsli * (float) $this->nilai) / 100;
        return max(0, $hargaAsli - $diskon);
    }

    public function hitungNilaiDiskon($hargaAsli)
    {
        if ($this->tipe === 'fixed') {
            return min((float) $this->nilai, $hargaAsli);
        }

        // Percent
        return ($hargaAsli * (float) $this->nilai) / 100;
    }

    public function isActive()
    {
        $now = Carbon::now();
        return $this->aktif && $now->between($this->mulai_berlaku, $this->berakhir);
    }

    public function getFormatDiskonAttribute()
    {
        if ($this->tipe === 'fixed') {
            return 'Rp ' . number_format((float) $this->nilai, 0, ',', '.');
        }

        return (float) $this->nilai . '%';
    }

    public function getPersenDiskonAttribute()
    {
        if ($this->tipe === 'percent') {
            return $this->nilai;
        }

        return null;
    }
}
