<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_prov extends Model implements AuditableContract
{

    protected $table = 'ref_prov';

    protected $fillable = [
        'regcode', 'provcode', 'regcode_9', 'provcode_9', 'provname', 'old_names', 'incomeclass', 'addedby', 'UserLevelID', 'status',
    ];

    use Auditable;

}
