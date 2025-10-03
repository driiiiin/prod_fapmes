<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Models\Project;


class OverallAreaReportService
{
    // DATA TABLE TAB
    public static function getDatatableData()
    {
        return Project::select([
                'projects.id',  // Explicitly specify table
                'projects.project_id',
                'projects.project_name',
                'projects.short_title',
                'projects.funding_source',
                'projects.donor',
                'projects.depdev',
                'projects.gph',
                'projects.fund_type',
                'projects.fund_management',
                'projects.management'
            ])
            ->leftJoin('financial_accomplishments as f', 'projects.project_id', '=', 'f.project_id')
            ->selectRaw('SUM(f.budget) as total_budget')
            ->selectSub(function($query) {
                $query->from('financial_accomplishments as f2')
                    ->select('budget')
                    ->whereColumn('f2.project_id', 'projects.project_id')
                    ->latest('created_at')
                    ->limit(1);
            }, 'latest_budget')
            ->groupBy([
                'projects.id',
                'projects.project_id',
                'projects.project_name',
                'projects.short_title',
                'projects.funding_source',
                'projects.donor',
                'projects.depdev',
                'projects.gph',
                'projects.fund_type',
                'projects.fund_management',
                'projects.management'
            ])
            ->orderBy('projects.created_at', 'DESC')
            ->get();
    }

    //FUNDING SOURCE TAB

    public static function getFundingSourceReport()
    {
        $projectSummary = DB::table('projects as p')
            ->select([
                'p.funding_source',
                'p.depdev',
                'p.gph',
                'p.management',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->groupBy(['p.funding_source', 'p.depdev', 'p.gph', 'p.management']);

        $overallSummary = DB::table('projects as p')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id');

        return DB::table('ref_funds as rn')
            ->select([
                'rn.funds_desc as funding_source',
                'n.depdev_desc as depdev',
                'g.gph_desc as gph',
                'm.management_desc as management',
                DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
            ])
            ->leftJoinSub($projectSummary, 'ps', 'rn.funds_desc', '=', 'ps.funding_source')
            ->crossJoinSub($overallSummary, 'os')
            ->leftJoin('ref_depdev as n', 'ps.depdev', '=', 'n.depdev_desc')
            ->leftJoin('ref_gph as g', 'ps.gph', '=', 'g.gph_desc')
            ->leftJoin('ref_management as m', 'ps.management', '=', 'm.management_desc')
            ->orderByDesc('total_budget')
            ->get();
    }

    //MANAGEMENT TAB
    public static function getCombinedManagementReports()
    {
        // ProjectSummary: Detailed breakdown by management, funding_source, depdev, gph
        $projectSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.management',
                'p.funding_source',
                'p.depdev',
                'p.gph',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget'),
                DB::raw('0 as is_management_only')
            ])
            ->groupBy(['p.management', 'p.funding_source', 'p.depdev', 'p.gph']);

        // ManagementOnly: Summary by management only
        $managementOnly = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.management',
                DB::raw('NULL as funding_source'),
                DB::raw('NULL as depdev'),
                DB::raw('NULL as gph'),
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget'),
                DB::raw('1 as is_management_only')
            ])
            ->groupBy('p.management');

        // Combined: Union of both queries
        $combined = $projectSummary->unionAll($managementOnly);

        // OverallSummary
        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        // Main query with joins
        $results = DB::table(DB::raw("({$combined->toSql()}) as c"))
            ->mergeBindings($combined)
            ->leftJoin('ref_management as m', 'c.management', '=', 'm.management_desc')
            ->leftJoin('ref_funds as rn', 'c.funding_source', '=', 'rn.funds_desc')
            ->leftJoin('ref_depdev as n', 'c.depdev', '=', 'n.depdev_desc')
            ->leftJoin('ref_gph as g', 'c.gph', '=', 'g.gph_desc')
            ->crossJoinSub($overallSummary, 'os')
            ->select([
                'm.management_desc as management',
                'rn.funds_desc as funding_source',
                'n.depdev_desc as depdev',
                'g.gph_desc as gph',
                DB::raw('COALESCE(c.project_count, 0) as project_count'),
                DB::raw('COALESCE(c.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(c.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(c.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage'),
                'c.is_management_only'
            ])
            ->orderBy('c.is_management_only')
            ->orderByDesc(DB::raw('COALESCE(c.total_budget, 0)'))
            ->get();

        return [
            'manages' => $results->where('is_management_only', 1)->values(),
            'managements' => $results->where('is_management_only', 0)->values()
        ];
    }

    //depdev TAB
    public static function getdepdevReports()
    {
        // depdevOnly: Summary by depdev only
        $depdevOnly = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.depdev',
                DB::raw('NULL as funding_source'),
                DB::raw('NULL as management'),
                DB::raw('NULL as gph'),
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget'),
                DB::raw('1 as is_depdev_only')
            ])
            ->groupBy('p.depdev');

        // depdevDetailed: Detailed breakdown
        $depdevDetailed = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.depdev',
                'p.funding_source',
                'p.management',
                'p.gph',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget'),
                DB::raw('0 as is_depdev_only')
            ])
            ->groupBy(['p.depdev', 'p.funding_source', 'p.management', 'p.gph']);

        // Combined: Union of both queries
        $combined = $depdevOnly->unionAll($depdevDetailed);

        // OverallSummary
        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        // Main query with joins
        $results = DB::table(DB::raw("({$combined->toSql()}) as c"))
            ->mergeBindings($combined)
            ->leftJoin('ref_depdev as n', 'c.depdev', '=', 'n.depdev_desc')
            ->leftJoin('ref_funds as rn', 'c.funding_source', '=', 'rn.funds_desc')
            ->leftJoin('ref_management as m', 'c.management', '=', 'm.management_desc')
            ->leftJoin('ref_gph as g', 'c.gph', '=', 'g.gph_desc')
            ->crossJoinSub($overallSummary, 'os')
            ->select([
                'n.depdev_desc as depdev',
                'rn.funds_desc as funding_source',
                'm.management_desc as management',
                'g.gph_desc as gph',
                DB::raw('COALESCE(c.project_count, 0) as project_count'),
                DB::raw('COALESCE(c.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(c.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(c.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage'),
                'c.is_depdev_only'
            ])
            ->orderByDesc('c.is_depdev_only')
            ->orderByDesc(DB::raw('COALESCE(c.total_budget, 0)'))
            ->get();

        return [
            'depdevs' => $results->where('is_depdev_only', 1)->values(),
            'depdevclasses' => $results->where('is_depdev_only', 0)->values()
        ];
    }

    //GPH_IMPLEMENTED TAB
    public static function getGphReports()
    {
        // GphOnly: Summary by gph only
        $gphOnly = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.gph',
                DB::raw('NULL as funding_source'),
                DB::raw('NULL as management'),
                DB::raw('NULL as depdev'),
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget'),
                DB::raw('1 as is_gph_only')
            ])
            ->groupBy('p.gph');

        // GphDetailed: Detailed breakdown
        $gphDetailed = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.gph',
                'p.funding_source',
                'p.management',
                'p.depdev',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget'),
                DB::raw('0 as is_gph_only')
            ])
            ->groupBy(['p.gph', 'p.funding_source', 'p.management', 'p.depdev']);

        // Combined: Union of both queries
        $combined = $gphOnly->unionAll($gphDetailed);

        // OverallSummary
        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        // Main query with joins
        $results = DB::table(DB::raw("({$combined->toSql()}) as c"))
            ->mergeBindings($combined)
            ->leftJoin('ref_gph as g', 'c.gph', '=', 'g.gph_desc')
            ->leftJoin('ref_funds as rn', 'c.funding_source', '=', 'rn.funds_desc')
            ->leftJoin('ref_management as m', 'c.management', '=', 'm.management_desc')
            ->leftJoin('ref_depdev as n', 'c.depdev', '=', 'n.depdev_desc')
            ->crossJoinSub($overallSummary, 'os')
            ->select([
                'g.gph_desc as gph',
                'rn.funds_desc as funding_source',
                'm.management_desc as management',
                'n.depdev_desc as depdev',
                DB::raw('COALESCE(c.project_count, 0) as project_count'),
                DB::raw('COALESCE(c.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(c.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(c.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage'),
                'c.is_gph_only'
            ])
            ->orderByDesc('c.is_gph_only')
            ->orderByDesc(DB::raw('COALESCE(c.total_budget, 0)'))
            ->get();

        return [
            'gphs' => $results->where('is_gph_only', 1)->values(),
            'gphclasses' => $results->where('is_gph_only', 0)->values()
        ];
    }

     //LEVEL1 TAB
     public static function getLevel1Reports()
     {
         $projectSummary = DB::table('projects as p')
             ->join('levels as l', 'p.project_id', '=', 'l.project_id')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 'l.level1',
                 DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                 DB::raw('SUM(f.budget) as total_budget')
             ])
             ->groupBy('l.level1');

         $overallSummary = DB::table('projects as p')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                 DB::raw('SUM(f.budget) as total_budget')
             ]);

         return DB::table('ref_level1 as rn')
             ->leftJoinSub($projectSummary, 'ps', 'rn.level1_desc', '=', 'ps.level1')
             ->crossJoinSub($overallSummary, 'os')
             ->select([
                 'rn.level1_desc as level1',
                 DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                 DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                 DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                 DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
             ])
             ->orderByDesc('total_budget')
             ->get();
     }

     public static function getLevel1ImplementationReports()
     {
         $projectSummary = DB::table('projects as p')
             ->join('levels as l', 'p.project_id', '=', 'l.project_id')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 'l.level1',
                 'p.funding_source',
                 'p.management',
                 'p.depdev',
                 'p.gph',
                 DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                 DB::raw('SUM(f.budget) as total_budget')
             ])
             ->groupBy(['l.level1', 'p.funding_source', 'p.management', 'p.depdev', 'p.gph']);

         $overallSummary = DB::table('projects as p')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                 DB::raw('SUM(f.budget) as total_budget')
             ]);

         return DB::table('ref_level1 as rn')
             ->leftJoinSub($projectSummary, 'ps', 'rn.level1_desc', '=', 'ps.level1')
             ->crossJoinSub($overallSummary, 'os')
             ->leftJoin('ref_funds as f', 'ps.funding_source', '=', 'f.funds_desc')
             ->leftJoin('ref_management as m', 'ps.management', '=', 'm.management_desc')
             ->leftJoin('ref_depdev as n', 'ps.depdev', '=', 'n.depdev_desc')
             ->leftJoin('ref_gph as g', 'ps.gph', '=', 'g.gph_desc')
             ->select([
                 'rn.level1_desc as level1',
                 'f.funds_desc as funding_source',
                 'm.management_desc as management',
                 'n.depdev_desc as depdev',
                 'g.gph_desc as gph',
                 DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                 DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                 DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                 DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
             ])
             ->orderByDesc('total_budget')
             ->get();
     }

     //LEVEL2 TAB
     public static function getLevel2Reports()
     {
         $projectSummary = DB::table('projects as p')
             ->join('levels as l', 'p.project_id', '=', 'l.project_id')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 'l.level2',
                 DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                 DB::raw('SUM(f.budget) as total_budget')
             ])
             ->groupBy('l.level2');

         $overallSummary = DB::table('projects as p')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                 DB::raw('SUM(f.budget) as total_budget')
             ]);

         return DB::table('ref_level2 as rn')
             ->leftJoinSub($projectSummary, 'ps', 'rn.level2_desc', '=', 'ps.level2')
             ->crossJoinSub($overallSummary, 'os')
             ->select([
                 'rn.level2_desc as level2',
                 DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                 DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                 DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                 DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
             ])
             ->orderByDesc('total_budget')
             ->get();
     }

     public static function getLevel2ImplementationReports()
     {
         $projectSummary = DB::table('projects as p')
             ->join('levels as l', 'p.project_id', '=', 'l.project_id')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 'l.level2',
                 'p.funding_source',
                 'p.management',
                 'p.depdev',
                 'p.gph',
                 DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                 DB::raw('SUM(f.budget) as total_budget')
             ])
             ->groupBy(['l.level2', 'p.funding_source', 'p.management', 'p.depdev', 'p.gph']);

         $overallSummary = DB::table('projects as p')
             ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
             ->select([
                 DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                 DB::raw('SUM(f.budget) as total_budget')
             ]);

         return DB::table('ref_level2 as rn')
             ->leftJoinSub($projectSummary, 'ps', 'rn.level2_desc', '=', 'ps.level2')
             ->crossJoinSub($overallSummary, 'os')
             ->leftJoin('ref_funds as f', 'ps.funding_source', '=', 'f.funds_desc')
             ->leftJoin('ref_management as m', 'ps.management', '=', 'm.management_desc')
             ->leftJoin('ref_depdev as n', 'ps.depdev', '=', 'n.depdev_desc')
             ->leftJoin('ref_gph as g', 'ps.gph', '=', 'g.gph_desc')
             ->select([
                 'rn.level2_desc as level2',
                 'f.funds_desc as funding_source',
                 'm.management_desc as management',
                 'n.depdev_desc as depdev',
                 'g.gph_desc as gph',
                 DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                 DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                 DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                 DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
             ])
             ->orderByDesc('total_budget')
             ->get();
     }

     //LEVEL3 TAB
    public static function getLevel3Reports()
    {
        $projectSummary = DB::table('projects as p')
            ->join('levels as l', 'p.project_id', '=', 'l.project_id')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'l.level3',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->groupBy('l.level3');

        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        return DB::table('ref_level3 as rn')
            ->leftJoinSub($projectSummary, 'ps', 'rn.level3_desc', '=', 'ps.level3')
            ->crossJoinSub($overallSummary, 'os')
            ->select([
                'rn.level3_desc as level3',
                DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
            ])
            ->orderByDesc('total_budget')
            ->get();
    }

    public static function getLevel3ImplementationReports()
    {
        $projectSummary = DB::table('projects as p')
            ->join('levels as l', 'p.project_id', '=', 'l.project_id')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'l.level3',
                'p.funding_source',
                'p.management',
                'p.depdev',
                'p.gph',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->groupBy(['l.level3', 'p.funding_source', 'p.management', 'p.depdev', 'p.gph']);

        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        return DB::table('ref_level3 as rn')
            ->leftJoinSub($projectSummary, 'ps', 'rn.level3_desc', '=', 'ps.level3')
            ->crossJoinSub($overallSummary, 'os')
            ->leftJoin('ref_funds as f', 'ps.funding_source', '=', 'f.funds_desc')
            ->leftJoin('ref_management as m', 'ps.management', '=', 'm.management_desc')
            ->leftJoin('ref_depdev as n', 'ps.depdev', '=', 'n.depdev_desc')
            ->leftJoin('ref_gph as g', 'ps.gph', '=', 'g.gph_desc')
            ->select([
                'rn.level3_desc as level3',
                'f.funds_desc as funding_source',
                'm.management_desc as management',
                'n.depdev_desc as depdev',
                'g.gph_desc as gph',
                DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
            ])
            ->orderByDesc('total_budget')
            ->get();
    }

    //FUNDS TAB
    public static function getFundsReports()
    {
        $projectSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.fund_type',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->groupBy('p.fund_type');

        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        return DB::table('ref_funds_type as rt')
            ->leftJoinSub($projectSummary, 'ps', 'rt.funds_type_desc', '=', 'ps.fund_type')
            ->crossJoinSub($overallSummary, 'os')
            ->select([
                'rt.funds_type_desc as type_of_funds',
                DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
            ])
            ->orderByDesc('total_budget')
            ->get();
    }

    public static function getFundTypesReports()
    {
        $projectSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                'p.fund_type',
                'p.funding_source',
                'p.management',
                'p.depdev',
                'p.gph',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->groupBy(['p.fund_type', 'p.funding_source', 'p.management', 'p.depdev', 'p.gph']);

        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        return DB::table('ref_funds_type as ft')
            ->leftJoinSub($projectSummary, 'ps', 'ft.funds_type_desc', '=', 'ps.fund_type')
            ->crossJoinSub($overallSummary, 'os')
            ->leftJoin('ref_funds as rn', 'ps.funding_source', '=', 'rn.funds_desc')
            ->leftJoin('ref_depdev as n', 'ps.depdev', '=', 'n.depdev_desc')
            ->leftJoin('ref_gph as g', 'ps.gph', '=', 'g.gph_desc')
            ->leftJoin('ref_management as m', 'ps.management', '=', 'm.management_desc')
            ->select([
                'ft.funds_type_desc as type_of_funds',
                'rn.funds_desc as funding_source',
                'n.depdev_desc as depdev',
                'g.gph_desc as gph',
                'm.management_desc as management',
                DB::raw('COALESCE(ps.project_count, 0) as project_count'),
                DB::raw('COALESCE(ps.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(ps.project_count, 0) * 100.0 / NULLIF(os.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(ps.total_budget, 0) * 100.0 / NULLIF(os.total_budget, 0) as budget_percentage')
            ])
            ->orderByDesc('total_budget')
            ->get();
    }

    //FUND & MANAGEMENT TAB
    public static function getFundManagementReports()
    {
        // Project Summary subquery
        $projectSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw("CONCAT(p.management, ', ', p.fund_type) as fund_management"),
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->groupBy(['p.fund_type', 'p.management']);

        // Overall Summary subquery
        $overallSummary = DB::table('projects as p')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->select([
                DB::raw('COUNT(DISTINCT p.project_id) as total_projects'),
                DB::raw('SUM(f.budget) as total_budget')
            ]);

        // Main query
        return DB::table(DB::raw("({$projectSummary->toSql()}) as ProjectSummary"))
            ->mergeBindings($projectSummary)
            ->crossJoinSub($overallSummary, 'OverallSummary')
            ->select([
                'ProjectSummary.fund_management as fund_and_management',
                DB::raw('COALESCE(ProjectSummary.project_count, 0) as project_count'),
                DB::raw('COALESCE(ProjectSummary.total_budget, 0) as total_budget'),
                DB::raw('COALESCE(ProjectSummary.project_count, 0) * 100.0 / NULLIF(OverallSummary.total_projects, 0) as count_percentage'),
                DB::raw('COALESCE(ProjectSummary.total_budget, 0) * 100.0 / NULLIF(OverallSummary.total_budget, 0) as budget_percentage')
            ])
            ->orderByDesc('total_budget')
            ->get();
    }
}


