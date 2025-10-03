<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_health_facility extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_health_facility';

    protected $fillable = [
        'health_facility_code', 'health_facility_desc',
    ];
}
