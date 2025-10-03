<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_level3 extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_level3';

    protected $fillable = [
        'level3_code', 'level3_desc'
    ];
}

