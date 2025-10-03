<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'project_id',
        'project_name',
        'short_title',
        'funding_source',
        'donor',
        'depdev',
        'management',
        'gph',
        'fund_type',
        'fund_management',
        'desk_officer',
        'sector',
        'sites',
        'site_specific_reg',
        'site_specific_prov',
        'site_specific_city',
        'agreement',
        'status',
        'outcome',
    ];


    public function implementationschedule()
    {
        return $this->hasMany(ImplementationSchedule::class, 'project_id')->select([
            'project_id',
            'project_name',
            'start_date',
            'end_date',
            'extension',
            'duration',
            'time_elapsed',
            'p_time_elapsed',
        ]);
    }

    public function levels()
    {
        return $this->hasMany(Level::class, 'project_id')->select([
            'project_id',
            'project_name',
            'level1',
            'level2',
            'level3',
            'l_budget',
            'outcome',
        ]);
    }

    public function financialaccomplishment()
    {
        return $this->hasMany(FinancialAccomplishment::class, 'project_id')->select([
            'project_id',
            'project_name',
            'budget',
            'orig_budget',
            'lp',
            'gp',
            'gph_counterpart',
            'disbursement',
            'p_disbursement',
        ]);
    }

    public function physicalaccomplishment()
    {
        return $this->hasMany(PhysicalAccomplishment::class, 'project_id')->select([
            'project_id',
            'project_name',
            'actual',
            'target',
            'p_actual',
        ]);
    }
}
