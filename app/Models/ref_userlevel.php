<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_userlevel extends Model implements AuditableContract
{
    use Auditable;
    //
    protected $table = 'ref_userlevel';

    protected $fillable = [
        'userlevel_code', 'userlevel_desc'
    ];

}
