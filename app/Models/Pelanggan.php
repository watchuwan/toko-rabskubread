<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Pelanggan extends Model
{
    use HasFactory, LogsActivity,Notifiable;

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

    protected $casts = [
        'email_verified_at' => 'datetime',
        'tanggal_lahir'     => 'date',
    ];

        public function getActivitylogOptions(): LogOptions
    {
        // Konfigurasi log untuk model ini
        return LogOptions::defaults()
            ->logAll() // log semua atribut
            ->useLogName('Pelanggan') // nama log
            ->setDescriptionForEvent(fn(string $eventName) => "Pengguna telah {$eventName}");
    }


}
