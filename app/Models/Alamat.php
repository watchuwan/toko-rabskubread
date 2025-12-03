<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamat';

    protected $fillable = [
        'pelanggan_id',
        'label',
        'nama_penerima',
        'telepon',
        'alamat',
        'kota',
        'provinsi',
        'kode_pos',
        'alamat_utama',
    ];

    protected function casts(): array
    {
        return [
            'alamat_utama' => 'boolean',
        ];
    }

    // Relationships
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }

    // Helper Methods
    public function alamatLengkap()
    {
        return "{$this->alamat}, {$this->kota}, {$this->provinsi} {$this->kode_pos}";
    }
}
