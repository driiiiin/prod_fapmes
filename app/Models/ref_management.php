<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_management extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_management';

    protected $fillable = [
        'management_code',
        'management_desc'
    ];
}

