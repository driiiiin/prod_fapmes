<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class FinancialAccomplishment extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = [
        'project_id',
        'project_name',
        'orig_budget',
        'currency',
        'rate',
        'budget',
        'lp',
        'gp',
        'gph_counterpart',
        'disbursement',
        'p_disbursement',
        'encoded_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

// In App\Models\FinancialAccomplishment

public static function sumLatestBudget($year = null)
{
    return self::query()
        ->join('projects', 'financial_accomplishments.project_id', '=', 'projects.project_id')
        ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
        ->where('projects.status', '!=', 'Pipeline')
        ->when($year, function ($query, $year) {
            $query->whereYear('projects.completed_date', $year);
        })
        ->sum('financial_accomplishments.budget') ?? 0;
}

public static function sumLatestDisbursements($year = null)
{
    return self::query()
        ->join('projects', 'financial_accomplishments.project_id', '=', 'projects.project_id')
        ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
        ->where('projects.status', '!=', 'Pipeline')
        ->when($year, function ($query, $year) {
            $query->whereYear('projects.completed_date', $year);
        })
        ->sum('financial_accomplishments.disbursement') ?? 0;
}

    public static function sumLatestBudgetByProvince($province)
    {
        return self::query()
            ->join('projects', 'financial_accomplishments.project_id', '=', 'projects.id')
            ->select(\DB::raw('SUM(financial_accomplishments.budget) as total'))
            ->where('projects.site_specific_prov', 'like', '%' . $province . '%')
            ->whereIn('financial_accomplishments.id', function ($query) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('financial_accomplishments')
                    ->groupBy('project_id');
            })
            ->value('total') ?? 0;
    }

    public static function sumLatestBudgetPerFundingSource($year = null)
    {
        return self::query()
            ->join('projects', 'financial_accomplishments.project_id', '=', 'projects.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->where('projects.status', '!=', 'Pipeline')
            ->when($year, function ($query, $year) {
                $query->whereYear('projects.completed_date', $year);
            })
            ->groupBy('projects.funding_source')
            ->select('projects.funding_source', \DB::raw('SUM(financial_accomplishments.budget) as total_budget'))
            ->pluck('total_budget', 'projects.funding_source')
            ->toArray();
    }

    public static function sumLatestDisbursementPerFundingSource($year = null)
    {
        return self::query()
            ->join('projects', 'financial_accomplishments.project_id', '=', 'projects.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->where('projects.status', '!=', 'Pipeline')
            ->when($year, function ($query, $year) {
                $query->whereYear('projects.completed_date', $year);
            })
            ->groupBy('projects.funding_source')
            ->select('projects.funding_source', \DB::raw('SUM(financial_accomplishments.disbursement) as total_disbursement'))
            ->pluck('total_disbursement', 'projects.funding_source')
            ->toArray();
    }

    /**
     * Latest budget totals per funding source filtered by status (Pipeline/Active/Completed).
     */
    public static function sumLatestBudgetPerFundingSourceByStatus(?string $status, $year = null): array
    {
        return self::query()
            ->join('projects', 'financial_accomplishments.project_id', '=', 'projects.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->when($status, function ($query, $status) {
                $query->where('projects.status', $status);
            })
            ->when($year, function ($query, $year) {
                $query->whereYear('projects.completed_date', $year);
            })
            ->groupBy('projects.funding_source')
            ->select('projects.funding_source', DB::raw('SUM(financial_accomplishments.budget) as total_budget'))
            ->pluck('total_budget', 'projects.funding_source')
            ->toArray();
    }

}
