<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_gph extends Model
{
    protected $table = 'ref_gph';

    protected $fillable = [
        'gph_code', 'gph_desc'
    ];
}

