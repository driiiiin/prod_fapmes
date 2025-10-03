<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_funds extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_funds';

    protected $fillable = [
        'funds_id', 'funds_code', 'funds_desc'
    ];
}

