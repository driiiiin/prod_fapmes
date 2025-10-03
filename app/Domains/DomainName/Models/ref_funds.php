<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_funds extends Model
{
    protected $table = 'ref_funds';

    protected $fillable = [
        'funds_id', 'funds_code', 'funds_desc'
    ];
}

