<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Level extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;
    protected $fillable = [
        'project_id',
        'project_name',
        'level1',
        'level2',
        'level3',
        'l_budget',
        'encoded_by',
        'outcome',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

