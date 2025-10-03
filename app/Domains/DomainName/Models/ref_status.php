<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_status extends Model
{
    protected $table = 'ref_status';

    protected $fillable = [
        'status_code', 'status_desc',
    ];
}

