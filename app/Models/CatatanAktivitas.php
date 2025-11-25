<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Activity as BaseActivity;


class CatatanAktivitas extends BaseActivity
{
    //
    protected $casts = [
        "properties" => "array",
    ];
}
