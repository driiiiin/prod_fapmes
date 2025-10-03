<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAccomplishment extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'project_name',
        'orig_budget',
        'currency',
        'budget',
        'lp',
        'gp',
        'gph_counterpart',
        'disbursement',
        'p_disbursement',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

