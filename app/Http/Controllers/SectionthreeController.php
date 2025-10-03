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

class SectionthreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
    //   * @return \Illuminate\Http\Response
    //   */
    // public function index()
    // {
    //     return view('app.sectionthree.index');
    // }

    /**
     * Display funding source page
     *
     * @return \Illuminate\Http\Response
     */
    public function fundingSource()
    {
        $projects = DB::table('projects as p')
            ->select([
                'p.id as project_id',
                DB::raw('YEAR(ls.latest_start_date) as year'),
                'p.donor',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->leftJoin(DB::raw('(SELECT project_id, MAX(start_date) as latest_start_date
                               FROM implementation_schedules
                               GROUP BY project_id) ls'),
                      'p.project_id', '=', 'ls.project_id')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->groupBy('p.id', DB::raw('YEAR(ls.latest_start_date)'), 'p.donor')
            ->orderBy('year')
            ->orderBy('p.id')
            ->get();

        return view('app.sectionthree.funding_source.index', compact('projects'));
    }

    /**
     * Display health area page
     *
     * @return \Illuminate\Http\Response
     */
    public function healthArea()
    {
        $projects = DB::table('projects as p')
            ->select([
                'p.id as project_id',
                DB::raw('YEAR(ls.latest_start_date) as year'),
                'l.level1',
                DB::raw('COUNT(DISTINCT p.project_id) as project_count'),
                DB::raw('SUM(f.budget) as total_budget')
            ])
            ->leftJoin(DB::raw('(SELECT project_id, MAX(start_date) as latest_start_date
                               FROM implementation_schedules
                               GROUP BY project_id) ls'),
                      'p.project_id', '=', 'ls.project_id')
            ->leftJoin('financial_accomplishments as f', 'p.project_id', '=', 'f.project_id')
            ->leftJoin('levels as l', 'p.project_id', '=', 'l.project_id')
            ->groupBy('p.id', DB::raw('YEAR(ls.latest_start_date)'), 'l.level1')
            ->orderBy('year')
            ->orderBy('p.id')
            ->get();

        return view('app.sectionthree.health_area.index', compact('projects'));
    }
}