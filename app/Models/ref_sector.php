<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_sector extends Model implements AuditableContract
{
    use Auditable;
    //
    protected $table = 'ref_sector';

    protected $fillable = [
        'sector_code', 'sector_desc'
    ];

}
