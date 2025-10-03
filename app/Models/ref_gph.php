<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_gph extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_gph';

    protected $fillable = [
        'gph_code', 'gph_desc'
    ];
}

