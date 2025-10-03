<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_uhc extends Model
{
    protected $table = 'ref_uhc';

    protected $fillable = [
        'uhc_code', 'uhc_desc',
    ];
}

