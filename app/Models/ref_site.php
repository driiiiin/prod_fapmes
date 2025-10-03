<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_site extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_site';

    protected $fillable = [
        'site_code', 'site_desc',
    ];
}

