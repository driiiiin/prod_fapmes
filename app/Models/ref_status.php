<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_status extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_status';

    protected $fillable = [
        'status_code', 'status_desc',
    ];
}

