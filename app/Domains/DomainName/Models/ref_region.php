<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_region extends Model
{

    protected $table = 'ref_region';

    protected $fillable = [
        'regcode', 'regcode_9', 'nscb_reg_name', 'regabbrev', 'UserLevelID', 'addedby', 'status'
    ];

}
