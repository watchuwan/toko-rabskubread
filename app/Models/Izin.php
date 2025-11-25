<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as BasePermission;


class Izin extends BasePermission
{
    use LogsActivity;
    protected $table = "permissions";

    public function getActivitylogOptions(): LogOptions
    {
        // Konfigurasi log untuk model ini
        return LogOptions::defaults()
            ->logAll() // log semua atribut
            ->useLogName('Hak Akses') // nama log
            ->setDescriptionForEvent(fn(string $eventName) => "Pengguna telah {$eventName}");
    }
}
