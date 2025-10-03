<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_funds_type extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_funds_type';

    protected $fillable = [
        'funds_type_code', 'funds_type_desc'
    ];
}

