<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ImplementationSchedule;
use App\Models\Level;
use App\Models\FinancialAccomplishment;
use App\Models\PhysicalAccomplishment;
use App\Models\ref_funds;
use App\Models\ref_depdev;
use App\Models\ref_management;
use App\Models\ref_gph;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Services\OverallAreaReportService;
class SectiontwoController extends Controller
{
    //SECTION 2

    // OVERALL AREA DISTRIBUTION
    public function overallAreaDistribution()
    {
        $projects = Project::orderByDesc('created_at')->get();

        //FUNDING SOURCE TAB
        $fundingsources = OverallAreaReportService::getFundingSourceReport();

        //MANAGEMENT TAB
        $managementreports = OverallAreaReportService::getCombinedManagementReports();

        // Extract the separated results
        $manages = $managementreports['manages'];
        $managements = $managementreports['managements'];

        // depdev TAB
        $depdevReports = OverallAreaReportService::getDepdevReports();

        // Extract the separated results
        $depdevs = $depdevReports['depdevs'];
        $depdevclasses = $depdevReports['depdevclasses'];

        //GPH_IMPLEMENTED TAB
        $gphReports = OverallAreaReportService::getGphReports();

        // Extract the separated results
        $gphs = $gphReports['gphs'];
        $gph_implements = $gphReports['gphclasses'];

        //LEVEL1 TAB
        $level1s = OverallAreaReportService::getLevel1Reports();
        $level1_implements = OverallAreaReportService::getLevel1ImplementationReports();

        //LEVEL2 TAB
        $level2s = OverallAreaReportService::getLevel2Reports();
        $level2_implements = OverallAreaReportService::getLevel2ImplementationReports();

        //LEVEL3 TAB
        $level3s = OverallAreaReportService::getLevel3Reports();
        $level3_implements = OverallAreaReportService::getLevel3ImplementationReports();

        //FUNDS TAB
        $funds = OverallAreaReportService::getFundsReports();
        $fundtypes = OverallAreaReportService::getFundTypesReports();

        //FUND & MANAGEMENT TAB
        $fundmanagements = OverallAreaReportService::getFundManagementReports();

        //DATA TABLE TAB
        $datatable = OverallAreaReportService::getDatatableData();

        return view('app.sectiontwo.overall_area_distribution.index', compact(
            'projects', 'datatable', 'fundingsources', 'managements', 'manages',
            'depdevs', 'depdevclasses', 'gphs', 'gph_implements', 'level1s',
            'level1_implements', 'level2s', 'level2_implements', 'level3s',
            'level3_implements', 'funds', 'fundtypes', 'fundmanagements'
        ));
    }

    public function overallAreaDistributionReport()
    {
        //FUNDING SOURCE TAB
        $fundingsources = OverallAreaReportService::getFundingSourceReport();

        //MANAGEMENT TAB
        $managementreports = OverallAreaReportService::getCombinedManagementReports();

        // Extract the separated results
        $manages = $managementreports['manages'];
        $managements = $managementreports['managements'];

        // depdev TAB
        $depdevReports = OverallAreaReportService::getDepdevReports();

        // Extract the separated results
        $depdevs = $depdevReports['depdevs'];
        $depdevclasses = $depdevReports['depdevclasses'];

        //GPH_IMPLEMENTED TAB
        $gphReports = OverallAreaReportService::getGphReports();

        // Extract the separated results
        $gphs = $gphReports['gphs'];
        $gph_implements = $gphReports['gphclasses'];

        //LEVEL1 TAB
        $level1s = OverallAreaReportService::getLevel1Reports();
        $level1_implements = OverallAreaReportService::getLevel1ImplementationReports();

        //LEVEL2 TAB
        $level2s = OverallAreaReportService::getLevel2Reports();
        $level2_implements = OverallAreaReportService::getLevel2ImplementationReports();

        //LEVEL3 TAB
        $level3s = OverallAreaReportService::getLevel3Reports();
        $level3_implements = OverallAreaReportService::getLevel3ImplementationReports();

        //FUNDS TAB
        $funds = OverallAreaReportService::getFundsReports();
        $fundtypes = OverallAreaReportService::getFundTypesReports();

        //FUND & MANAGEMENT TAB
        $fundmanagements = OverallAreaReportService::getFundManagementReports();

        return view('app.sectiontwo.overall_area_distribution.overall_area_report', compact(
            'fundingsources', 'managements', 'manages',
            'depdevs', 'depdevclasses', 'gphs', 'gph_implements', 'level1s',
            'level1_implements', 'level2s', 'level2_implements', 'level3s',
            'level3_implements', 'funds', 'fundtypes', 'fundmanagements'
        ));
    }

    public function geographicDistribution()
    {
        if (auth()->user()->userlevel == -1 || auth()->user()->userlevel == 3 || auth()->user()->userlevel == 4) {
            $projects = Project::orderByDesc('created_at')->get();
        } else {
            $projects = Project::where('encoded_by', '=', DB::table('users')->where('userlevel', 2)->value('username'))->get();
        }
        return view('app.sectiontwo.geographic_distribution.index', compact('projects'));
    }

    public function healthAreasDistribution()
    {
        return view('app.sectiontwo.health_areas_distribution.index');
    }

    public function getFilteredHealthAreasData(Request $request)
    {
        $filters = $request->only(['funding_source', 'management', 'level1', 'level2', 'level3']);

        // Get accurate project counts for funding_source -> management using the correct method
        $fundingManagementData = Project::getProjectCountByFundingSourceAndManagement();

        // Get accurate level data for management -> levels flows using the correct method and filters
        $levelData = Project::getFilteredProjectCountByManagementAndLevels($filters);

        // Prepare data for 3 separate Sankey diagrams
        $sankeyDataLevel1 = [];
        $sankeyDataLevel2 = [];
        $sankeyDataLevel3 = [];

        // First, add funding_source -> management flows using accurate counts
        foreach ($fundingManagementData as $row) {
            if ($row->project_count > 0) {
                // Apply filters to funding_source -> management flow
                $includeFunding = !isset($filters['funding_source']) || $filters['funding_source'] === 'All' || $filters['funding_source'] === $row->funding_source;
                $includeManagement = !isset($filters['management']) || $filters['management'] === 'All' || $filters['management'] === $row->management;

                if ($includeFunding && $includeManagement) {
                    $key = $row->funding_source . '||' . $row->management;
                    $sankeyDataLevel1[$key] = $row->project_count;
                    $sankeyDataLevel2[$key] = $row->project_count;
                    $sankeyDataLevel3[$key] = $row->project_count;
                }
            }
        }

        // Then add management -> levels flows
        foreach ($levelData as $row) {
            if ($row->project_count > 0) {
                // management -> level1 (if exists)
                if ($row->level1) {
                    $key2 = $row->management . '||' . $row->level1;
                    $sankeyDataLevel1[$key2] = $row->project_count;
                }

                // management -> level2 (if exists)
                if ($row->level2) {
                    $key3 = $row->management . '||' . $row->level2;
                    $sankeyDataLevel2[$key3] = $row->project_count;
                }

                // management -> level3 (if exists)
                if ($row->level3) {
                    $key4 = $row->management . '||' . $row->level3;
                    $sankeyDataLevel3[$key4] = $row->project_count;
                }
            }
        }

        // Convert to data rows for each chart
        $dataRowsLevel1 = [];
        foreach ($sankeyDataLevel1 as $key => $count) {
            [$from, $to] = explode('||', $key);
            $dataRowsLevel1[] = [$from, $to, $count];
        }

        $dataRowsLevel2 = [];
        foreach ($sankeyDataLevel2 as $key => $count) {
            [$from, $to] = explode('||', $key);
            $dataRowsLevel2[] = [$from, $to, $count];
        }

        $dataRowsLevel3 = [];
        foreach ($sankeyDataLevel3 as $key => $count) {
            [$from, $to] = explode('||', $key);
            $dataRowsLevel3[] = [$from, $to, $count];
        }

        return response()->json([
            'level1' => $dataRowsLevel1,
            'level2' => $dataRowsLevel2,
            'level3' => $dataRowsLevel3
        ]);
    }

    public function healthAreasDistributionReport()
    {
        // Get accurate project counts for funding_source -> management using the correct method
        $fundingManagementData = Project::getProjectCountByFundingSourceAndManagement();

        // Get accurate level data for management -> levels flows using the correct method
        $levelData = Project::getProjectCountByManagementAndLevels();

        // Prepare data for 3 separate Sankey diagrams
        $sankeyDataLevel1 = [];
        $sankeyDataLevel2 = [];
        $sankeyDataLevel3 = [];

        // First, add funding_source -> management flows using accurate counts
        foreach ($fundingManagementData as $row) {
            if ($row->project_count > 0) {
                $key = $row->funding_source . '||' . $row->management;
                $sankeyDataLevel1[$key] = $row->project_count;
                $sankeyDataLevel2[$key] = $row->project_count;
                $sankeyDataLevel3[$key] = $row->project_count;
            }
        }

        // Then add management -> levels flows using accurate counts
        foreach ($levelData as $row) {
            if ($row->project_count > 0) {
                // management -> level1 (if exists)
                if ($row->level1) {
                    $key2 = $row->management . '||' . $row->level1;
                    $sankeyDataLevel1[$key2] = $row->project_count;
                }

                // management -> level2 (if exists)
                if ($row->level2) {
                    $key3 = $row->management . '||' . $row->level2;
                    $sankeyDataLevel2[$key3] = $row->project_count;
                }

                // management -> level3 (if exists)
                if ($row->level3) {
                    $key4 = $row->management . '||' . $row->level3;
                    $sankeyDataLevel3[$key4] = $row->project_count;
                }
            }
        }

        // Convert to data rows for each chart
        $dataRowsLevel1 = [];
        foreach ($sankeyDataLevel1 as $key => $count) {
            [$from, $to] = explode('||', $key);
            $dataRowsLevel1[] = [$from, $to, $count];
        }

        $dataRowsLevel2 = [];
        foreach ($sankeyDataLevel2 as $key => $count) {
            [$from, $to] = explode('||', $key);
            $dataRowsLevel2[] = [$from, $to, $count];
        }

        $dataRowsLevel3 = [];
        foreach ($sankeyDataLevel3 as $key => $count) {
            [$from, $to] = explode('||', $key);
            $dataRowsLevel3[] = [$from, $to, $count];
        }

        return view('app.sectiontwo.health_areas_distribution.health_area_report', compact(
            'dataRowsLevel1', 'dataRowsLevel2', 'dataRowsLevel3'
        ));
    }



}
