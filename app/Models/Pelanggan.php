<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pelanggan extends Authenticatable
{
    use Notifiable;

    protected $table = 'pelanggan';

    protected $fillable = [
        'nama',
        'email',
        'password',
        'telepon',
        'tanggal_lahir',
        'jenis_kelamin',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'tanggal_lahir' => 'date',
        ];
    }

    // Relationships
    public function keranjang()
    {
        return $this->hasOne(Keranjang::class);
    }

    public function alamat()
    {
        return $this->hasMany(Alamat::class);
    }

    public function alamatUtama()
    {
        return $this->hasOne(Alamat::class)->where('alamat_utama', true);
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class);
    }
}
