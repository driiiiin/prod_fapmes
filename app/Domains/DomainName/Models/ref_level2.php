<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_level2 extends Model
{
    protected $table = 'ref_level2';

    protected $fillable = [
        'level1_code', 'level1_desc', 'level2_code', 'level2_desc'
    ];
}

