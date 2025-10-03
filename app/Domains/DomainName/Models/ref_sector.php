<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_sector extends Model
{
    //
    protected $table = 'ref_sector';

    protected $fillable = [
        'sector_code', 'sector_desc'
    ];

}
