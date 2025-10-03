<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_site extends Model
{
    protected $table = 'ref_site';

    protected $fillable = [
        'site_code', 'site_desc',
    ];
}

