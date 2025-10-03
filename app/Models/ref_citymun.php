<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_citymun extends Model implements AuditableContract
{

    protected $table = 'ref_citymun';

    protected $fillable = [
        'regcode', 'provcode', 'citycode', 'regcode_9', 'provcode_9', 'citycode_9', 'cityname', 'geographic_level', 'old_names', 'cityclass', 'incomeclass', 'addedby', 'UserLevelID', 'status',
    ];

    use Auditable;

}

