<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Models\Role as BaseRole;
class Peran extends BaseRole
{
    //
    use LogsActivity;

    protected $table = "roles";

    public function getActivitylogOptions(): LogOptions
    {
        // Konfigurasi log untuk model ini
        return LogOptions::defaults()
            ->logAll() // log semua atribut
            ->useLogName('Peran') // nama log
            ->setDescriptionForEvent(fn(string $eventName) => "Pengguna telah {$eventName}");
    }
}
