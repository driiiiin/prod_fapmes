<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_prov extends Model
{

    protected $table = 'ref_prov';

    protected $fillable = [
        'regcode', 'provcode', 'regcode_9', 'provcode_9', 'provname', 'old_names', 'incomeclass', 'addedby', 'UserLevelID', 'status',
    ];

}
