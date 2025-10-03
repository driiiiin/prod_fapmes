<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class Project extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
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
        'alignment',
        'environmental',
        'health_facility',
        'development_objectives',
        'sector',
        'sites',
        'site_specific_reg',
        'site_specific_prov',
        'site_specific_city',
        'agreement',
        'status',
        'completed_date',
        'encoded_by',
        'outcome',
    ];



    public function setAgreementAttribute($value)
    {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old file if it exists
            if ($this->agreement && Storage::exists('public/' . $this->agreement)) {
                Storage::delete('public/' . $this->agreement);
            }
            // Store the new file and save the path
            $this->attributes['agreement'] = $value->store('agreements', 'public');
        } else {
            // If no new file is uploaded, keep the existing file path
            $this->attributes['agreement'] = $value;
        }
    }

    public function getAgreementUrlAttribute()
    {
        return $this->agreement ? Storage::url($this->agreement) : null;
    }

    // Remove the deletion logic if you don't want to delete the file
    protected static function boot()
    {
        parent::boot();
        // static::deleting(function ($project) {
        //     if ($project->agreement) {
        //         Storage::delete($project->agreement);
        //     }
        // });
    }

    public function implementationschedule()
    {
        return $this->hasMany(ImplementationSchedule::class, 'project_id')->select([
            'project_id',
            'project_name',
            'start_date',
            // 'interim_date',
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
            'currency',
            'rate',
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
            'project_type',
            'year',
            'quarter',
            'weight',
            'actual',
            'target',
            'project_type1',
            'year1',
            'quarter1',
            'weight1',
            'actual1',
            'target1',
            'overall_accomplishment',
            'overall_target',
            'slippage',
            'remarks',
            'slippage_end_of_quarter',
            'outcome_file',
        ]);
    }

    public static function getTotalBudgetByFundingSource()
    {
        return self::select('projects.funding_source')
            ->join('financial_accomplishments', 'projects.project_id', '=', 'financial_accomplishments.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->selectRaw('SUM(financial_accomplishments.budget) as total_budget')
            ->groupBy('projects.funding_source')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->funding_source => $item->total_budget];
            });
    }

    public static function getTotalBudgetByManagement()
    {
        return self::select('projects.management')
            ->join('financial_accomplishments', 'projects.project_id', '=', 'financial_accomplishments.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->selectRaw('SUM(financial_accomplishments.budget) as total_budget')
            ->groupBy('projects.management')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->management => $item->total_budget];
            });
    }

    public static function getTotalBudgetByDepDev()
    {
        return self::select('projects.depdev')
            ->join('financial_accomplishments', 'projects.project_id', '=', 'financial_accomplishments.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->selectRaw('SUM(financial_accomplishments.budget) as total_budget')
            ->groupBy('projects.depdev')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->depdev => $item->total_budget];
            });
    }

    public static function getTotalBudgetByGphImplemented()
    {
        return self::select('projects.gph')
            ->join('financial_accomplishments', 'projects.project_id', '=', 'financial_accomplishments.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->selectRaw('SUM(financial_accomplishments.budget) as total_budget')
            ->groupBy('projects.gph')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->gph => $item->total_budget];
            });
    }

    public static function getTotalBudgetByLevel1()
    {
        return self::select('projects.level1')
            ->join('financial_accomplishments', 'projects.project_id', '=', 'financial_accomplishments.project_id')
            ->whereRaw('financial_accomplishments.created_at = (SELECT MAX(created_at) FROM financial_accomplishments fa2 WHERE fa2.project_id = financial_accomplishments.project_id)')
            ->selectRaw('SUM(financial_accomplishments.budget) as total_budget')
            ->groupBy('projects.level1')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->level1 => $item->total_budget];
            });
    }

    // public static function getProjectCountByRegion($region)
    // {
    //     return self::where(function($query) use ($region) {
    //         $query->where('site_specific_reg', 'like', '%' . $region->nscb_reg_name . '%')
    //               ->orWhere('site_specific_reg', $region->nscb_reg_name)
    //               ->orWhereRaw('FIND_IN_SET(?, site_specific_reg)', [$region->nscb_reg_name]);
    //     })->count();
    // }

    public static function getProjectCountByRegion($region, $year = null)
{
    $query = self::query();

    $query->where(function($q) use ($region) {
        $q->where('site_specific_reg', 'like', '%' . $region->nscb_reg_name . '%')
          ->orWhere('site_specific_reg', $region->nscb_reg_name)
          ->orWhereRaw('FIND_IN_SET(?, site_specific_reg)', [$region->nscb_reg_name]);
    });

    // Apply year filter if provided
    if ($year) {
        $query->where('status', 'Completed')
              ->whereYear('completed_date', $year);
    }

    return $query->count();
}

    // public static function getProjectCountByProvince($province)
    // {
    //     return self::where(function($query) use ($province) {
    //         $query->where('site_specific_prov', 'like', '%' . $province->provname . '%')
    //               ->orWhere('site_specific_prov', $province->provname)
    //               ->orWhereRaw('FIND_IN_SET(?, site_specific_prov)', [$province->provname]);
    //     })->count();
    // }

    // public static function getProjectCountByCity($city)
    // {
    //     return self::where(function($query) use ($city) {
    //         $query->where('site_specific_city', 'like', '%' . $city->cityname . '%')
    //               ->orWhere('site_specific_city', $city->cityname)
    //               ->orWhereRaw('FIND_IN_SET(?, site_specific_city)', [$city->cityname]);
    //     })->count();
    // }


    // public static function getProjectCountByYear()
    // {
    //     return self::selectRaw('YEAR(created_at) as year, COUNT(DISTINCT project_id) as count')
    //         ->groupBy('year')
    //         ->orderBy('year')
    //         ->get()
    //         ->mapWithKeys(function ($item) {
    //             return [$item->year => $item->count];
    //         });
    // }


   public static function getProjectCountByRegionAndProvince()
{
    // First, get all provinces from the reference table
    $provinces = DB::table('ref_prov as p')
        ->join('ref_region as r', 'p.regcode', '=', 'r.regcode')
        ->select('r.nscb_reg_name AS region_name', 'p.provname')
        ->orderBy('r.nscb_reg_name')
        ->orderBy('p.provname')
        ->get();

    $results = [];

    foreach ($provinces as $province) {
        // Count projects for this province using multiple matching strategies
        $projectCount = self::where(function($query) use ($province) {
            $query->where('site_specific_prov', 'like', '%' . $province->provname . '%')
                  ->orWhere('site_specific_prov', $province->provname)
                  ->orWhereRaw('FIND_IN_SET(?, site_specific_prov)', [$province->provname])
                  ->orWhereRaw('FIND_IN_SET(?, REPLACE(site_specific_prov, " ", ""))', [$province->provname])
                  ->orWhere('site_specific_prov', 'Nationwide')
                  ->orWhere('site_specific_prov', 'Multi-Regional');
        })->count();

        $results[] = (object) [
            'region_name' => $province->region_name,
            'provname' => $province->provname,
            'project_count' => $projectCount
        ];
    }

    return collect($results);
}

public static function getProjectCountByFundingSourceAndManagement()
{
    // Get all unique funding sources and management types from reference tables
    $fundingSources = DB::table('ref_funds')->pluck('funds_desc');
    $managementTypes = DB::table('ref_management')->pluck('management_desc');

    $results = [];

    foreach ($fundingSources as $fundingSource) {
        foreach ($managementTypes as $managementType) {
            $projectCount = self::where(function($query) use ($fundingSource, $managementType) {
                $query->where(function($q) use ($fundingSource) {
                    $q->where('funding_source', 'like', '%' . $fundingSource . '%')
                      ->orWhere('funding_source', $fundingSource)
                      ->orWhereRaw('FIND_IN_SET(?, funding_source)', [$fundingSource]);
                })->where(function($q) use ($managementType) {
                    $q->where('management', 'like', '%' . $managementType . '%')
                      ->orWhere('management', $managementType)
                      ->orWhereRaw('FIND_IN_SET(?, management)', [$managementType]);
                });
            })->count();

            $results[] = (object) [
                'funding_source' => $fundingSource,
                'management' => $managementType,
                'project_count' => $projectCount
            ];
        }
    }

    return collect($results);
}

public static function getProjectCountByManagementAndLevels()
{
    // Get all unique management types from reference table
    $managementTypes = DB::table('ref_management')->pluck('management_desc');

    $results = [];

    foreach ($managementTypes as $managementType) {
        // Get level1 counts for this management type
        $level1Counts = DB::table('projects')
            ->join('levels', 'projects.project_id', '=', 'levels.project_id')
            ->leftJoin('ref_level1', 'levels.level1', '=', 'ref_level1.level1_code')
            ->where('projects.management', $managementType)
            ->whereNotNull('levels.level1')
            ->where('levels.level1', '!=', '')
            ->select(
                DB::raw('COALESCE(ref_level1.level1_desc, levels.level1) as level1_desc'),
                DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
            )
            ->groupBy('levels.level1', 'ref_level1.level1_desc')
            ->get();

        foreach ($level1Counts as $row) {
            $results[] = (object) [
                'management' => $managementType,
                'level1' => $row->level1_desc,
                'level2' => null,
                'level3' => null,
                'project_count' => $row->project_count
            ];
        }

        // Get level2 counts for this management type
        $level2Counts = DB::table('projects')
            ->join('levels', 'projects.project_id', '=', 'levels.project_id')
            ->leftJoin('ref_level2', 'levels.level2', '=', 'ref_level2.level2_code')
            ->where('projects.management', $managementType)
            ->whereNotNull('levels.level2')
            ->where('levels.level2', '!=', '')
            ->select(
                DB::raw('COALESCE(ref_level2.level2_desc, levels.level2) as level2_desc'),
                DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
            )
            ->groupBy('levels.level2', 'ref_level2.level2_desc')
            ->get();

        foreach ($level2Counts as $row) {
            $results[] = (object) [
                'management' => $managementType,
                'level1' => null,
                'level2' => $row->level2_desc,
                'level3' => null,
                'project_count' => $row->project_count
            ];
        }

        // Get level3 counts for this management type
        $level3Counts = DB::table('projects')
            ->join('levels', 'projects.project_id', '=', 'levels.project_id')
            ->leftJoin('ref_level3', 'levels.level3', '=', 'ref_level3.level3_code')
            ->where('projects.management', $managementType)
            ->whereNotNull('levels.level3')
            ->where('levels.level3', '!=', '')
            ->select(
                DB::raw('COALESCE(ref_level3.level3_desc, levels.level3) as level3_desc'),
                DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
            )
            ->groupBy('levels.level3', 'ref_level3.level3_desc')
            ->get();

        foreach ($level3Counts as $row) {
            $results[] = (object) [
                'management' => $managementType,
                'level1' => null,
                'level2' => null,
                'level3' => $row->level3_desc,
                'project_count' => $row->project_count
            ];
        }
    }

    return collect($results);
}

public static function getProjectCountByFundingSourceManagementAndLevels()
{
    // Get all unique funding sources and management types from reference tables
    $fundingSources = DB::table('ref_funds')->pluck('funds_desc');
    $managementTypes = DB::table('ref_management')->pluck('management_desc');

    $results = [];

    foreach ($fundingSources as $fundingSource) {
        foreach ($managementTypes as $managementType) {
            // Use the same logic as getProjectCountByFundingSourceAndManagement() for accurate project count
            $projectCount = self::where(function($query) use ($fundingSource, $managementType) {
                $query->where(function($q) use ($fundingSource) {
                    $q->where('funding_source', 'like', '%' . $fundingSource . '%')
                      ->orWhere('funding_source', $fundingSource)
                      ->orWhereRaw('FIND_IN_SET(?, funding_source)', [$fundingSource]);
                })->where(function($q) use ($managementType) {
                    $q->where('management', 'like', '%' . $managementType . '%')
                      ->orWhere('management', $managementType)
                      ->orWhereRaw('FIND_IN_SET(?, management)', [$managementType]);
                });
            })->count();

            // Only proceed if there are projects for this combination
            if ($projectCount > 0) {
                // Get level data for projects with this funding source and management
                $levelCounts = DB::table('projects')
                    ->leftJoin('levels', 'projects.project_id', '=', 'levels.project_id')
                    ->leftJoin('ref_level1', 'levels.level1', '=', 'ref_level1.level1_code')
                    ->leftJoin('ref_level2', 'levels.level2', '=', 'ref_level2.level2_code')
                    ->leftJoin('ref_level3', 'levels.level3', '=', 'ref_level3.level3_code')
                    ->where(function($query) use ($fundingSource) {
                        $query->where('projects.funding_source', 'like', '%' . $fundingSource . '%')
                              ->orWhere('projects.funding_source', $fundingSource)
                              ->orWhereRaw('FIND_IN_SET(?, projects.funding_source)', [$fundingSource]);
                    })
                    ->where('projects.management', $managementType)
                    ->select(
                        'levels.level1',
                        'levels.level2',
                        'levels.level3',
                        'ref_level1.level1_desc',
                        'ref_level2.level2_desc',
                        'ref_level3.level3_desc',
                        DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
                    )
                    ->groupBy('levels.level1', 'levels.level2', 'levels.level3', 'ref_level1.level1_desc', 'ref_level2.level2_desc', 'ref_level3.level3_desc')
                    ->get();

                foreach ($levelCounts as $row) {
                    if ($row->level1 && $row->project_count > 0) {
                        $results[] = (object) [
                            'funding_source' => $fundingSource,
                            'management' => $managementType,
                            'level1' => $row->level1_desc ?: $row->level1,
                            'level2' => $row->level2_desc ?: $row->level2,
                            'level3' => $row->level3_desc ?: $row->level3,
                            'project_count' => $row->project_count
                        ];
                    }
                }
            }
        }
    }

    return collect($results);
}

public static function getFilteredProjectCountByFundingSourceManagementAndLevels($filters = [])
{
    // Get all unique funding sources and management types from reference tables
    $fundingSources = DB::table('ref_funds')->pluck('funds_desc');
    $managementTypes = DB::table('ref_management')->pluck('management_desc');

    $results = [];

    foreach ($fundingSources as $fundingSource) {
        // Skip if funding source filter is set and doesn't match
        if (isset($filters['funding_source']) && $filters['funding_source'] !== 'All' && $filters['funding_source'] !== $fundingSource) {
            continue;
        }

        foreach ($managementTypes as $managementType) {
            // Skip if management filter is set and doesn't match
            if (isset($filters['management']) && $filters['management'] !== 'All' && $filters['management'] !== $managementType) {
                continue;
            }

            // Use the same logic as getProjectCountByFundingSourceAndManagement() for accurate project count
            $projectCount = self::where(function($query) use ($fundingSource, $managementType) {
                $query->where(function($q) use ($fundingSource) {
                    $q->where('funding_source', 'like', '%' . $fundingSource . '%')
                      ->orWhere('funding_source', $fundingSource)
                      ->orWhereRaw('FIND_IN_SET(?, funding_source)', [$fundingSource]);
                })->where(function($q) use ($managementType) {
                    $q->where('management', 'like', '%' . $managementType . '%')
                      ->orWhere('management', $managementType)
                      ->orWhereRaw('FIND_IN_SET(?, management)', [$managementType]);
                });
            })->count();

            // Only proceed if there are projects for this combination
            if ($projectCount > 0) {
                // Build the query with filters
                $query = DB::table('projects')
                    ->leftJoin('levels', 'projects.project_id', '=', 'levels.project_id')
                    ->leftJoin('ref_level1', 'levels.level1', '=', 'ref_level1.level1_code')
                    ->leftJoin('ref_level2', 'levels.level2', '=', 'ref_level2.level2_code')
                    ->leftJoin('ref_level3', 'levels.level3', '=', 'ref_level3.level3_code')
                    ->where(function($q) use ($fundingSource) {
                        $q->where('projects.funding_source', 'like', '%' . $fundingSource . '%')
                          ->orWhere('projects.funding_source', $fundingSource)
                          ->orWhereRaw('FIND_IN_SET(?, projects.funding_source)', [$fundingSource]);
                    })
                    ->where('projects.management', $managementType);

                // Apply level1 filter if set
                if (isset($filters['level1']) && $filters['level1'] !== 'All') {
                    $query->where(function($q) use ($filters) {
                        $q->where('ref_level1.level1_desc', $filters['level1'])
                          ->orWhere('levels.level1', $filters['level1']);
                    });
                }

                // Apply level2 filter if set
                if (isset($filters['level2']) && $filters['level2'] !== 'All') {
                    $query->where(function($q) use ($filters) {
                        $q->where('ref_level2.level2_desc', $filters['level2'])
                          ->orWhere('levels.level2', $filters['level2']);
                    });
                }

                // Apply level3 filter if set
                if (isset($filters['level3']) && $filters['level3'] !== 'All') {
                    $query->where(function($q) use ($filters) {
                        $q->where('ref_level3.level3_desc', $filters['level3'])
                          ->orWhere('levels.level3', $filters['level3']);
                    });
                }

                $levelCounts = $query->select(
                        'levels.level1',
                        'levels.level2',
                        'levels.level3',
                        'ref_level1.level1_desc',
                        'ref_level2.level2_desc',
                        'ref_level3.level3_desc',
                        DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
                    )
                    ->groupBy('levels.level1', 'levels.level2', 'levels.level3', 'ref_level1.level1_desc', 'ref_level2.level2_desc', 'ref_level3.level3_desc')
                    ->get();

                foreach ($levelCounts as $row) {
                    if ($row->level1 && $row->project_count > 0) {
                        $results[] = (object) [
                            'funding_source' => $fundingSource,
                            'management' => $managementType,
                            'level1' => $row->level1_desc ?: $row->level1,
                            'level2' => $row->level2_desc ?: $row->level2,
                            'level3' => $row->level3_desc ?: $row->level3,
                            'project_count' => $row->project_count
                        ];
                    }
                }
            }
        }
    }

    return collect($results);
}

public static function getFilteredProjectCountByManagementAndLevels($filters = [])
{
    $managementTypes = DB::table('ref_management')->pluck('management_desc');
    $results = [];

    foreach ($managementTypes as $managementType) {
        // Base query for this management type
        $baseQuery = DB::table('projects')
            ->join('levels', 'projects.project_id', '=', 'levels.project_id')
            ->where('projects.management', $managementType);

        // Level 1
        $level1Query = clone $baseQuery;
        $level1Query->leftJoin('ref_level1', 'levels.level1', '=', 'ref_level1.level1_code');
        if (!empty($filters['level1']) && $filters['level1'] !== 'All') {
            $level1Query->where(function($q) use ($filters) {
                $q->where('levels.level1', $filters['level1'])
                  ->orWhere('ref_level1.level1_desc', $filters['level1']);
            });
        }
        $level1Counts = $level1Query
            ->whereNotNull('levels.level1')
            ->where('levels.level1', '!=', '')
            ->select(
                DB::raw('COALESCE(ref_level1.level1_desc, levels.level1) as level1_desc'),
                DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
            )
            ->groupBy('levels.level1', 'ref_level1.level1_desc')
            ->get();
        foreach ($level1Counts as $row) {
            $results[] = (object) [
                'management' => $managementType,
                'level1' => $row->level1_desc,
                'level2' => null,
                'level3' => null,
                'project_count' => $row->project_count
            ];
        }

        // Level 2
        $level2Query = clone $baseQuery;
        $level2Query->leftJoin('ref_level2', 'levels.level2', '=', 'ref_level2.level2_code');
        if (!empty($filters['level2']) && $filters['level2'] !== 'All') {
            $level2Query->where(function($q) use ($filters) {
                $q->where('levels.level2', $filters['level2'])
                  ->orWhere('ref_level2.level2_desc', $filters['level2']);
            });
        }
        $level2Counts = $level2Query
            ->whereNotNull('levels.level2')
            ->where('levels.level2', '!=', '')
            ->select(
                DB::raw('COALESCE(ref_level2.level2_desc, levels.level2) as level2_desc'),
                DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
            )
            ->groupBy('levels.level2', 'ref_level2.level2_desc')
            ->get();
        foreach ($level2Counts as $row) {
            $results[] = (object) [
                'management' => $managementType,
                'level1' => null,
                'level2' => $row->level2_desc,
                'level3' => null,
                'project_count' => $row->project_count
            ];
        }

        // Level 3
        $level3Query = clone $baseQuery;
        $level3Query->leftJoin('ref_level3', 'levels.level3', '=', 'ref_level3.level3_code');
        if (!empty($filters['level3']) && $filters['level3'] !== 'All') {
            $level3Query->where(function($q) use ($filters) {
                $q->where('levels.level3', $filters['level3'])
                  ->orWhere('ref_level3.level3_desc', $filters['level3']);
            });
        }
        $level3Counts = $level3Query
            ->whereNotNull('levels.level3')
            ->where('levels.level3', '!=', '')
            ->select(
                DB::raw('COALESCE(ref_level3.level3_desc, levels.level3) as level3_desc'),
                DB::raw('COUNT(DISTINCT projects.project_id) as project_count')
            )
            ->groupBy('levels.level3', 'ref_level3.level3_desc')
            ->get();
        foreach ($level3Counts as $row) {
            $results[] = (object) [
                'management' => $managementType,
                'level1' => null,
                'level2' => null,
                'level3' => $row->level3_desc,
                'project_count' => $row->project_count
            ];
        }
    }
    return collect($results);
}






}
