<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_funds_type extends Model
{
    protected $table = 'ref_funds_type';

    protected $fillable = [
        'funds_type_code', 'funds_type_desc'
    ];
}

