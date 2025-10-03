<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_environmental extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_environmental';

    protected $fillable = [
        'environmental_code', 'environmental_desc',
    ];
}
