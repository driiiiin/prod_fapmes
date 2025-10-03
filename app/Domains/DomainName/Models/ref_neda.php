<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_depdev extends Model
{
    protected $table = 'ref_depdev';

    protected $fillable = [
        'depdev_code', 'depdev_desc',
    ];
}

