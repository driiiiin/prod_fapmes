<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ref_currency extends Model implements AuditableContract
{
    use Auditable;

    protected $table = 'ref_currency';

    protected $fillable = [
        'currency_code', 'currency_desc',
    ];
}
