<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_depdev extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_depdev';

    protected $fillable = [
        'depdev_code', 'depdev_desc',
    ];
}

