<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ref_citymun extends Model
{

    protected $table = 'ref_citymun';

    protected $fillable = [
        'regcode', 'provcode', 'citycode', 'regcode_9', 'provcode_9', 'citycode_9', 'cityname', 'geographic_level', 'old_names', 'cityclass', 'incomeclass', 'addedby', 'UserLevelID', 'status',
    ];

}

