<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_alignment extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_alignment';

    protected $fillable = [
        'alignment_code', 'alignment_desc',
    ];
}
