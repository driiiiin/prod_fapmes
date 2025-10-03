<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_uhc extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_uhc';

    protected $fillable = [
        'uhc_code', 'uhc_desc',
    ];
}

