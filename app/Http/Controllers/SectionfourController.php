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
use Barryvdh\DomPDF\Facade\Pdf;

class SectionfourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report()
    {
        return view('app.sectionfour.index');
    }

    // In SectionfourController.php

public function generateDashboardSummaryReport(Request $request)
{
    $year = $request->input('year');
    $format = $request->input('format', 'html');

    // Gather the same data as in dashboard.blade.php
    $filterYear = $year;
    $projectQuery = \App\Models\Project::query();
    if ($filterYear) {
        $projectQuery->where('status', 'Completed')->whereYear('completed_date', $filterYear);
    }
    $filteredProjects = $projectQuery->get();

    // For counts and stats, use $filteredProjects instead of all projects
    $totalProjects = $filteredProjects->count();

    // For status breakdowns, always use completed projects for the year if filtered, else all
    $pipelineCount = $filteredProjects->where('status', 'Pipeline')->count();
    $activeCount = $filteredProjects->where('status', 'Active')->count();
    $completedCount = $filteredProjects->where('status', 'Completed')->count();

    $pipelinePercentage = $totalProjects > 0 ? ($pipelineCount / $totalProjects) * 100 : 0;
    $activePercentage = $totalProjects > 0 ? ($activeCount / $totalProjects) * 100 : 0;
    $completedPercentage = $totalProjects > 0 ? ($completedCount / $totalProjects) * 100 : 0;

    $data = [
        'filteredProjects' => $filteredProjects,
        'totalProjects' => $totalProjects,
        'pipelineCount' => $pipelineCount,
        'activeCount' => $activeCount,
        'completedCount' => $completedCount,
        'pipelinePercentage' => $pipelinePercentage,
        'activePercentage' => $activePercentage,
        'completedPercentage' => $completedPercentage,
        'filterYear' => $filterYear,
        'recentProjects' => \App\Models\Project::select('projects.project_id', 'projects.short_title', 'projects.status')
            ->join('implementation_schedules', 'projects.project_id', '=', 'implementation_schedules.project_id')
            ->whereRaw('implementation_schedules.start_date = (
                SELECT MAX(start_date)
                FROM implementation_schedules is2
                WHERE is2.project_id = projects.project_id
            )')
            ->orderByDesc('implementation_schedules.start_date')
            ->limit(5)
            ->get(),
    ];

    if ($format === 'pdf') {
        $pdf = Pdf::loadView('reports.dashboard_summary', $data);

        // Set proper headers for PDF
        return $pdf->stream('dashboard_summary_report_'.($year ?: 'all').'.pdf', [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="dashboard_summary_report_'.($year ?: 'all').'.pdf"'
        ]);
    } else {
        return view('reports.dashboard_summary', $data);
    }
}

public function previewDashboardSummaryReport(Request $request)
{
    $year = $request->input('year');

    // Gather the same data as in dashboard.blade.php
    $filterYear = $year;
    $projectQuery = \App\Models\Project::query();
    if ($filterYear) {
        $projectQuery->where('status', 'Completed')->whereYear('completed_date', $filterYear);
    }
    $filteredProjects = $projectQuery->get();

    // For counts and stats, use $filteredProjects instead of all projects
    $totalProjects = $filteredProjects->count();

    // For status breakdowns, always use completed projects for the year if filtered, else all
    $pipelineCount = $filteredProjects->where('status', 'Pipeline')->count();
    $activeCount = $filteredProjects->where('status', 'Active')->count();
    $completedCount = $filteredProjects->where('status', 'Completed')->count();

    $pipelinePercentage = $totalProjects > 0 ? ($pipelineCount / $totalProjects) * 100 : 0;
    $activePercentage = $totalProjects > 0 ? ($activeCount / $totalProjects) * 100 : 0;
    $completedPercentage = $totalProjects > 0 ? ($completedCount / $totalProjects) * 100 : 0;

    $data = [
        'filteredProjects' => $filteredProjects,
        'totalProjects' => $totalProjects,
        'pipelineCount' => $pipelineCount,
        'activeCount' => $activeCount,
        'completedCount' => $completedCount,
        'pipelinePercentage' => $pipelinePercentage,
        'activePercentage' => $activePercentage,
        'completedPercentage' => $completedPercentage,
        'filterYear' => $filterYear,
        'recentProjects' => \App\Models\Project::select('projects.project_id', 'projects.short_title', 'projects.status')
            ->join('implementation_schedules', 'projects.project_id', '=', 'implementation_schedules.project_id')
            ->whereRaw('implementation_schedules.start_date = (
                SELECT MAX(start_date)
                FROM implementation_schedules is2
                WHERE is2.project_id = projects.project_id
            )')
            ->orderByDesc('implementation_schedules.start_date')
            ->limit(5)
            ->get(),
    ];

    return view('reports.dashboard_summary_preview', $data);
}

// public function generateDashboardSummaryPreviewAjax(Request $request)
// {
//     $year = $request->input('year');

//     // Gather the same data as in dashboard.blade.php
//     $filterYear = $year;
//     $projectQuery = \App\Models\Project::query();
//     if ($filterYear) {
//         $projectQuery->where('status', 'Completed')->whereYear('completed_date', $filterYear);
//     }
//     $filteredProjects = $projectQuery->get();

//     // For counts and stats, use $filteredProjects instead of all projects
//     $totalProjects = $filteredProjects->count();

//     // For status breakdowns, always use completed projects for the year if filtered, else all
//     $pipelineCount = $filteredProjects->where('status', 'Pipeline')->count();
//     $activeCount = $filteredProjects->where('status', 'Active')->count();
//     $completedCount = $filteredProjects->where('status', 'Completed')->count();

//     $pipelinePercentage = $totalProjects > 0 ? ($pipelineCount / $totalProjects) * 100 : 0;
//     $activePercentage = $totalProjects > 0 ? ($activeCount / $totalProjects) * 100 : 0;
//     $completedPercentage = $totalProjects > 0 ? ($completedCount / $totalProjects) * 100 : 0;

//     $data = [
//         'filteredProjects' => $filteredProjects,
//         'totalProjects' => $totalProjects,
//         'pipelineCount' => $pipelineCount,
//         'activeCount' => $activeCount,
//         'completedCount' => $completedCount,
//         'pipelinePercentage' => $pipelinePercentage,
//         'activePercentage' => $activePercentage,
//         'completedPercentage' => $completedPercentage,
//         'filterYear' => $filterYear,
//         'recentProjects' => \App\Models\Project::select('projects.project_id', 'projects.short_title', 'projects.status')
//             ->join('implementation_schedules', 'projects.project_id', '=', 'implementation_schedules.project_id')
//             ->whereRaw('implementation_schedules.start_date = (
//                 SELECT MAX(start_date)
//                 FROM implementation_schedules is2
//                 WHERE is2.project_id = projects.project_id
//             )')
//             ->orderByDesc('implementation_schedules.start_date')
//             ->limit(5)
//             ->get(),
//     ];

//     return view('reports.dashboard_summary_preview_content', $data);
// }

// Generate Monthly, Quarterly, and Yearly Reports
public function generateReport(Request $request)
{
    $type = $request->input('type'); // 'complete' (monthly), 'section1' (quarterly), 'section2' (yearly)
    $year = $request->input('year');
    $format = $request->input('format', 'html');

    $query = Project::query();
    if ($year && $year !== '') {
        $query->whereYear('completed_date', $year);
    }
    $query->whereNotNull('completed_date');
    $projects = $query->get();

    $grouped = [];
    if ($type === 'complete') {
        // Monthly
        $grouped = $projects->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->completed_date)->format('F');
        });
    } elseif ($type === 'section1') {
        // Quarterly
        $grouped = $projects->groupBy(function($item) {
            $month = \Carbon\Carbon::parse($item->completed_date)->month;
            $quarter = ceil($month / 3);
            return 'Q' . $quarter;
        });
    } elseif ($type === 'section2') {
        // Yearly
        $grouped = $projects->groupBy(function($item) {
            return \Carbon\Carbon::parse($item->completed_date)->format('Y');
        });
    }

    $data = [
        'type' => $type,
        'year' => $year,
        'grouped' => $grouped,
    ];

    if ($format === 'pdf') {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.generic_report', $data);
        return $pdf->stream('report_' . $type . '_' . ($year ?: 'all') . '.pdf');
    } else {
        return view('reports.generic_report', $data);
    }
}

}
