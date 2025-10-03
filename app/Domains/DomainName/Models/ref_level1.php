<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_level1 extends Model
{
    protected $table = 'protected_table';

    protected $fillable = [
        'level1_code', 'level1_desc'
    ];
}

