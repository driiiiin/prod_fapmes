<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_region extends Model implements AuditableContract
{

    protected $table = 'ref_region';

    protected $fillable = [
        'regcode', 'regcode_9', 'nscb_reg_name', 'regabbrev', 'UserLevelID', 'addedby', 'status',
    ];

    use Auditable;

}
