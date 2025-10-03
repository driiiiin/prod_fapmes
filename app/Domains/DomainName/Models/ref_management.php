<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_management extends Model
{
    protected $table = 'ref_management';

    protected $fillable = [
        'management_code',
        'management_desc'
    ];
}

