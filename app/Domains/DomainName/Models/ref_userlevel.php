<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_userlevel extends Model
{
    //
    protected $table = 'ref_userlevel';

    protected $fillable = [
        'userlevel_code', 'userlevel_desc'
    ];

}
