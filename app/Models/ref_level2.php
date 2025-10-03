<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_level2 extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_level2';

    protected $fillable = [
        'level1_code', 'level1_desc', 'level2_code', 'level2_desc'
    ];
}

