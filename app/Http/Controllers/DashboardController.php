<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\FinancialAccomplishment;
use App\Models\ImplementationSchedule;
use App\Models\PhysicalAccomplishment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
public function index()
{

    // // Get recent projects with their financial accomplishments
    // $recentProjects = Project::with('financialAccomplishment')
    //     ->latest()
    //     ->take(5)
    //     ->get(['project_id', 'short_title', 'status']);

    // $user = Auth::user();

    // if (in_array($user->userlevel, [-1, 2, 5, 6])) {
    //     // Admin/privileged users - get all records
    //     $recentProjects = Project::with('financialAccomplishment')
    //         ->latest()
    //         ->take(5)
    //         ->get(['project_id', 'short_title', 'status']);
    // } else {
    //     // Regular users - get only their own records or specific userlevel records
    //     $targetUserlevel = ($user->userlevel == 3) ? 3 : 4;

    // }

    // Rest of your existing chart data code...
    $currentYear = now()->year;
    $chartData = FinancialAccomplishment::query()
        ->join('implementation_schedules', 'financial_accomplishments.project_id', '=', 'implementation_schedules.project_id')
        ->selectRaw('MONTH(implementation_schedules.start_date) as month')
        ->selectRaw('SUM(financial_accomplishments.budget) as total_budget')
        ->whereYear('implementation_schedules.start_date', $currentYear)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    // Prepare chart data
    $months = [];
    $totals = [];
    for ($i = 1; $i <= 12; $i++) {
        $months[] = date('F', mktime(0, 0, 0, $i, 1));
        $totals[] = 0;
    }
    foreach ($chartData as $data) {
        $monthIndex = $data->month - 1;
        if (isset($totals[$monthIndex])) {
            $totals[$monthIndex] = $data->total_budget;
        }
    }

    // return view('dashboard', compact('recentProjects', 'months', 'totals'));
    return view('dashboard', compact( 'months', 'totals'));
}

// Add this new method to handle AJAX requests for different years
public function getFinancialAccomplishments(Request $request)
{
    $year = $request->input('year', now()->year);

    $chartData = FinancialAccomplishment::query()
        ->join('implementation_schedules', 'financial_accomplishments.project_id', '=', 'implementation_schedules.project_id')
        ->selectRaw('MONTH(implementation_schedules.start_date) as month')
        ->selectRaw('SUM(financial_accomplishments.budget) as total_budget')
        ->whereYear('implementation_schedules.start_date', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

    return response()->json($chartData);
}

public function physicalProjectList(Request $request)
{
    $type = $request->input('type'); // 'infra', 'non_infra', 'combined'
    $status = $request->input('status'); // 'behind', 'on_time', 'ahead', 'all'
    $year = $request->input('year', now()->year);

    // Build the query to get the latest physical accomplishments
    $query = PhysicalAccomplishment::query()
        ->join('projects', 'physical_accomplishments.project_id', '=', 'projects.project_id')
        ->whereRaw('physical_accomplishments.created_at = (
            SELECT MAX(created_at)
            FROM physical_accomplishments pa2
            WHERE pa2.project_id = physical_accomplishments.project_id
        )');

    // Only filter by year if year is set and not 'all'
    if ($year && $year !== 'all') {
        $query->whereYear('projects.completed_date', $year);
    }

    // Filter by project type based on weight fields
    if ($type === 'infra') {
        $query->where('physical_accomplishments.weight', 100)
              ->where(function($q) {
                  $q->whereNull('physical_accomplishments.weight1')
                    ->orWhere('physical_accomplishments.weight1', 0);
              });
    } elseif ($type === 'non_infra') {
        $query->where('physical_accomplishments.weight1', 100)
              ->where(function($q) {
                  $q->whereNull('physical_accomplishments.weight')
                    ->orWhere('physical_accomplishments.weight', 0);
              });
    } elseif ($type === 'combined') {
        $query->where('physical_accomplishments.weight', '!=', 0)
              ->where('physical_accomplishments.weight1', '!=', 0)
              ->where('physical_accomplishments.weight', '!=', 100)
              ->where('physical_accomplishments.weight1', '!=', 100);
    }

    // Filter by status
    if ($status && $status !== 'all') {
        if ($status === 'behind') {
            $query->where('physical_accomplishments.remarks', 'BEHIND');
        } elseif ($status === 'on_time') {
            $query->where('physical_accomplishments.remarks', 'ON-TIME');
        } elseif ($status === 'ahead') {
            $query->where('physical_accomplishments.remarks', 'AHEAD');
        }
    }

    // Get the results with project information
    $accomplishments = $query->select([
        'projects.id as id',
        'physical_accomplishments.project_id',
        'physical_accomplishments.project_name',
        'projects.short_title'
    ])->get();

    // Prepare response
    $result = $accomplishments->map(function ($accomp) {
        return [
            'id' => $accomp->id,
            'project_id' => $accomp->project_id,
            'short_title' => $accomp->short_title,
            'project_name' => $accomp->project_name,
        ];
    })->values();

    return response()->json($result);
}

public function fundingProjectList(Request $request)
{
    $fund = $request->input('fund');
    $status = $request->input('status');
    $year = $request->input('year', 'all');

    $query = Project::query();

    // Filter by funding source
    if ($fund) {
        $query->where('funding_source', $fund);
    }

    // Filter by status (only if status is not 'all')
    if ($status && $status !== 'all') {
        $query->where('status', $status);
    }

    // Filter by year if specified
    if ($year && $year !== 'all') {
        $query->whereYear('completed_date', $year);
    }

    $projects = $query->select([
        'id',
        'project_id',
        'short_title',
        'project_name'
    ])->get();

    $result = $projects->map(function ($project) {
        return [
            'id' => $project->id,
            'project_id' => $project->project_id,
            'short_title' => $project->short_title,
            'project_name' => $project->project_name,
        ];
    })->values();

    return response()->json($result);
}

public function fundTypeProjectList(Request $request)
{
    $fundType = $request->input('fund_type');
    $year = $request->input('year', 'all');

    $query = Project::query();

    // Filter by fund type
    if ($fundType) {
        $query->where('fund_type', $fundType);
    }

    // Filter by year if specified
    if ($year && $year !== 'all') {
        $query->whereYear('completed_date', $year);
    }

    $projects = $query->select([
        'id',
        'project_id',
        'short_title',
        'project_name'
    ])->get();

    $result = $projects->map(function ($project) {
        return [
            'id' => $project->id,
            'project_id' => $project->project_id,
            'short_title' => $project->short_title,
            'project_name' => $project->project_name,
        ];
    })->values();

    return response()->json($result);
}

public function geoProjectList(Request $request)
{
    $site = $request->input('site');
    $year = $request->input('year', 'all');

    $query = Project::query();

    // Filter by site
    if ($site) {
        $query->where('sites', $site);
    }

    // Filter by year if specified
    if ($year && $year !== 'all') {
        $query->whereYear('completed_date', $year);
    }

    $projects = $query->select([
        'id',
        'project_id',
        'short_title',
        'project_name'
    ])->get();

    $result = $projects->map(function ($project) {
        return [
            'id' => $project->id,
            'project_id' => $project->project_id,
            'short_title' => $project->short_title,
            'project_name' => $project->project_name,
        ];
    })->values();

    return response()->json($result);
}

public function regionProjectList(Request $request)
{
    $region = $request->input('region');
    $year = $request->input('year', 'all');

    $query = Project::query();

    // Filter by region using site_specific_reg (comma-separated region names)
    if ($region) {
        $query->where(function($q) use ($region) {
            $q->where('site_specific_reg', 'like', '%' . $region . '%')
              ->orWhere('site_specific_reg', $region)
              ->orWhereRaw('FIND_IN_SET(?, site_specific_reg)', [$region]);
        });
    }

    // Filter by year if specified
    if ($year && $year !== 'all') {
        $query->whereYear('completed_date', $year);
    }

    $projects = $query->select([
        'id',
        'project_id',
        'short_title',
        'project_name'
    ])->get();

    $result = $projects->map(function ($project) {
        return [
            'id' => $project->id,
            'project_id' => $project->project_id,
            'short_title' => $project->short_title,
            'project_name' => $project->project_name,
        ];
    })->values();

    return response()->json($result);
}

public function financialProjectList(Request $request)
{
    $type = $request->input('type'); // 'budget' or 'disbursement'
    $year = $request->input('year', 'all');

    // Build the query to get projects with financial accomplishments
    $query = Project::query()
        ->join('financial_accomplishments', 'projects.project_id', '=', 'financial_accomplishments.project_id')
        ->whereRaw('financial_accomplishments.created_at = (
            SELECT MAX(created_at)
            FROM financial_accomplishments fa2
            WHERE fa2.project_id = financial_accomplishments.project_id
        )');

    // Filter by type (budget or disbursement)
    if ($type === 'budget') {
        $query->where('financial_accomplishments.budget', '>', 0);
    } elseif ($type === 'disbursement') {
        $query->where('financial_accomplishments.disbursements', '>', 0);
    }

    // Filter by year if specified
    if ($year && $year !== 'all') {
        $query->whereYear('projects.completed_date', $year);
    }

    $projects = $query->select([
        'projects.project_id',
        'projects.short_title',
        'projects.project_name',
        'financial_accomplishments.budget',
        'financial_accomplishments.disbursements'
    ])->distinct()->get();

    $result = $projects->map(function ($project) {
        return [
            'project_id' => $project->project_id,
            'short_title' => $project->short_title,
            'project_name' => $project->project_name,
            'budget' => $project->budget,
            'disbursements' => $project->disbursements,
        ];
    })->values();

    return response()->json($result);
}

public function depdevProjectList(Request $request)
{
    $depdev = $request->input('depdev');
    $year = $request->input('year', 'all');

    $query = Project::query();
    if ($depdev) {
        $query->where('depdev', $depdev);
    }
    if ($year && $year !== 'all') {
        $query->whereYear('completed_date', $year);
    }
    $projects = $query->select(['id', 'project_id', 'short_title', 'project_name'])->get();
    $result = $projects->map(function ($project) {
        return [
            'id' => $project->id,
            'project_id' => $project->project_id,
            'short_title' => $project->short_title,
            'project_name' => $project->project_name,
        ];
    })->values();
    return response()->json($result);
}

public function managementProjectList(Request $request)
{
    $management = $request->input('management');
    $year = $request->input('year', 'all');

    $query = Project::query();
    if ($management) {
        $query->where('management', $management);
    }
    if ($year && $year !== 'all') {
        $query->whereYear('completed_date', $year);
    }
    $projects = $query->select(['id', 'project_id', 'short_title', 'project_name'])->get();
    $result = $projects->map(function ($project) {
        return [
            'id' => $project->id,
            'project_id' => $project->project_id,
            'short_title' => $project->short_title,
            'project_name' => $project->project_name,
        ];
    })->values();
    return response()->json($result);
}

/**
 * Return a list of projects for the DOH Portfolio modal by status and year (AJAX).
 */
public function portfolioProjectList(Request $request)
{
    $status = $request->query('status');
    $year = $request->query('year');

    $query = \App\Models\Project::query();
    if ($status && in_array($status, ['Pipeline', 'Active', 'Completed'])) {
        $query->where('status', $status);
    }
    if ($year && $year !== 'all') {
        $query->whereYear('completed_date', $year);
    }
    $projects = $query->select('id', 'project_id', 'short_title', 'project_name')->get();
    return response()->json($projects);
}

/**
 * Return a list of projects for Health Area Level 1 modal (AJAX).
 */
public function healthAreaLevel1ProjectList(Request $request)
{
    $level1 = $request->query('level1');
    $year = $request->query('year');

    $query = \App\Models\Project::query()
        ->join('levels', 'projects.project_id', '=', 'levels.project_id');

    if ($level1) {
        $query->where('levels.level1', $level1);
    }
    if ($year && $year !== 'all') {
        $query->whereYear('projects.completed_date', $year);
    }

    $projects = $query->select('projects.id', 'projects.project_id', 'projects.short_title', 'projects.project_name')
        ->distinct()
        ->get();

    return response()->json($projects);
}

/**
 * Return a list of projects for Health Area Level 2 modal (AJAX).
 */
public function healthAreaLevel2ProjectList(Request $request)
{
    $level2 = $request->query('level2');
    $year = $request->query('year');

    $query = \App\Models\Project::query()
        ->join('levels', 'projects.project_id', '=', 'levels.project_id');

    if ($level2) {
        $query->where('levels.level2', $level2);
    }
    if ($year && $year !== 'all') {
        $query->whereYear('projects.completed_date', $year);
    }

    $projects = $query->select('projects.id', 'projects.project_id', 'projects.short_title', 'projects.project_name')
        ->distinct()
        ->get();

    return response()->json($projects);
}

/**
 * Return a list of projects for Health Area Level 3 modal (AJAX).
 */
public function healthAreaLevel3ProjectList(Request $request)
{
    $level3 = $request->query('level3');
    $year = $request->query('year');

    $query = \App\Models\Project::query()
        ->join('levels', 'projects.project_id', '=', 'levels.project_id');

    if ($level3) {
        $query->where('levels.level3', $level3);
    }
    if ($year && $year !== 'all') {
        $query->whereYear('projects.completed_date', $year);
    }

    $projects = $query->select('projects.id', 'projects.project_id', 'projects.short_title', 'projects.project_name')
        ->distinct()
        ->get();

    return response()->json($projects);
}

}
