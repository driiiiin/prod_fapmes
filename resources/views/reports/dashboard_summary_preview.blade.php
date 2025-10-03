<x-app-layout>
    @php
        // Only allow access for userlevels -1, 2, 5, 6
        $allowedUserlevels = [-1, 2, 5, 6];
        if (!in_array(auth()->user()->userlevel, $allowedUserlevels)) {
            abort(403, 'Unauthorized');
        }
    @endphp
    <div class="container-fluid m-4 p-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-alt me-2"></i>
                        Dashboard Summary Report Preview
                    </h5>
                    <div class="d-flex gap-2">
                        <button onclick="window.print()" class="btn btn-primary btn-sm">
                            <i class="fa fa-print me-1"></i> Print Report
                        </button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
                            <i class="fa fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <p class="text-muted small mb-0 mt-2">
                    Year: <strong>{{ request('year') ?? 'All' }}</strong> |
                    Generated: {{ date('Y-m-d H:i') }} |
                    <span class="text-info">This is a preview. Use the print button to generate a printable version.</span>
                </p>
            </div>

            <div class="card-body p-0">
                <!-- Report Content -->
                <div class="report-content" style="background: white; padding: 20px;">
                    <!-- Report Header -->
                    <div class="report-header text-center mb-4">
                        <h1 style="color: #007bff; margin-bottom: 8px; font-size: 22px;">Dashboard Summary Report</h1>
                        <p class="text-muted small mb-2">Year: <strong>{{ request('year') ?? 'All' }}</strong> | Generated: {{ date('Y-m-d H:i') }}</p>
                    </div>

                    <!-- 1. DOH Portfolio (Project Portfolio Overview) -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">DOH Portfolio Overview</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Total Projects</th>
                                <th>Pipeline</th>
                                <th>Active</th>
                                <th>Completed</th>
                            </tr>
                            <tr>
                                <td>{{ $filteredProjects->count() }}</td>
                                <td>{{ $filteredProjects->where('status', 'Pipeline')->count() }}</td>
                                <td>{{ $filteredProjects->where('status', 'Active')->count() }}</td>
                                <td>{{ $filteredProjects->where('status', 'Completed')->count() }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- 2. Projects by Funding Source and Status -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Projects by Funding Source and Status</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Funding Source</th>
                                <th>Total</th>
                                <th>Pipeline</th>
                                <th>Active</th>
                                <th>Completed</th>
                            </tr>
                            @foreach(\App\Models\ref_funds::orderBy('funds_code')->get() as $fund)
                                @php
                                    $fundProjects = $filteredProjects->where('funding_source', $fund->funds_desc);
                                @endphp
                                <tr>
                                    <td>{{ $fund->funds_code }}</td>
                                    <td>{{ $fundProjects->count() }}</td>
                                    <td>{{ $fundProjects->where('status', 'Pipeline')->count() }}</td>
                                    <td>{{ $fundProjects->where('status', 'Active')->count() }}</td>
                                    <td>{{ $fundProjects->where('status', 'Completed')->count() }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- 3. Fund Types -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Projects by Fund Type</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Fund Type</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                            @php
                                $fundTypes = ['Loan', 'Grants'];
                            @endphp
                            @foreach($fundTypes as $type)
                                @php
                                    $count = $filteredProjects->where('fund_type', $type)->count();
                                    $percentage = $filteredProjects->count() > 0 ? ($count / $filteredProjects->count()) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $type }}</td>
                                    <td>{{ $count }}</td>
                                    <td>{{ number_format($percentage, 1) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- 4: DePDev Classification -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">DePDev Classification</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Classification</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                            @php
                                $totalProjectsForDepDev = $filteredProjects->count();
                            @endphp
                            @foreach(\App\Models\ref_depdev::all() as $depdev)
                                @php
                                    $projectCount = $filteredProjects->where('depdev', $depdev->depdev_desc)->count();
                                    $percentage = $totalProjectsForDepDev > 0 ? ($projectCount / $totalProjectsForDepDev) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $depdev->depdev_desc }}</td>
                                    <td>{{ $projectCount }}</td>
                                    <td>{{ number_format($percentage, 1) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- 5: Management Types -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Management Types</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Management Type</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                            @php
                                $totalProjectsForManagement = $filteredProjects->count();
                            @endphp
                            @foreach(\App\Models\ref_management::all() as $management)
                                @php
                                    $projectCount = $filteredProjects->where('management', $management->management_desc)->count();
                                    $percentage = $totalProjectsForManagement > 0 ? ($projectCount / $totalProjectsForManagement) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $management->management_desc }}</td>
                                    <td>{{ $projectCount }}</td>
                                    <td>{{ number_format($percentage, 1) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- 6. Geographical Distribution -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Geographical Distribution</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Site</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                            @php
                                $sites = ['Multi-Regional', 'Nationwide', 'Region-specific', 'DOH - Central Office'];
                            @endphp
                            @foreach($sites as $site)
                                @php
                                    $count = $filteredProjects->where('sites', $site)->count();
                                    $percentage = $filteredProjects->count() > 0 ? ($count / $filteredProjects->count()) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $site }}</td>
                                    <td>{{ $count }}</td>
                                    <td>{{ number_format($percentage, 1) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- 7. Projects per Region -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Projects per Region</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Region</th>
                                <th>Project Count</th>
                            </tr>
                            @foreach(\App\Models\ref_region::orderBy('nscb_reg_name')->get() as $region)
                                @php
                                    $count = \App\Models\Project::getProjectCountByRegion($region, request('year'));
                                @endphp
                                <tr>
                                    <td>{{ $region->nscb_reg_name }}</td>
                                    <td>{{ $count }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- Health Area Reports -->
                    <!-- Level 1 -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Health Area (Level 1)</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Health Area</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                            @php
                                $level1s = \App\Models\ref_level1::orderBy('level1_desc')->get();
                                $level1Counts = \App\Models\Level::whereIn('project_id', $filteredProjects->pluck('project_id'))
                                    ->whereNotNull('level1')
                                    ->select('level1', \DB::raw('COUNT(DISTINCT project_id) as count'))
                                    ->groupBy('level1')
                                    ->pluck('count', 'level1');
                                $totalProjectsForLevel1 = $filteredProjects->count();
                            @endphp
                            @foreach($level1s as $level1)
                                @php
                                    $count = $level1Counts[$level1->level1_desc] ?? 0;
                                    $percentage = $totalProjectsForLevel1 > 0 ? ($count / $totalProjectsForLevel1) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $level1->level1_desc }}</td>
                                    <td>{{ $count }}</td>
                                    <td>{{ number_format($percentage, 1) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- Level 2 -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Health Area (Level 2)</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Health Area</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                            @php
                                $level2s = \App\Models\ref_level2::orderBy('level2_desc')->get();
                                $level2Counts = \App\Models\Level::whereIn('project_id', $filteredProjects->pluck('project_id'))
                                    ->whereNotNull('level2')
                                    ->select('level2', \DB::raw('COUNT(DISTINCT project_id) as count'))
                                    ->groupBy('level2')
                                    ->pluck('count', 'level2');
                                $totalProjectsForLevel2 = $filteredProjects->count();
                            @endphp
                            @foreach($level2s as $level2)
                                @php
                                    $count = $level2Counts[$level2->level2_desc] ?? 0;
                                    $percentage = $totalProjectsForLevel2 > 0 ? ($count / $totalProjectsForLevel2) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $level2->level2_desc }}</td>
                                    <td>{{ $count }}</td>
                                    <td>{{ number_format($percentage, 1) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <!-- Level 3 -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Health Systems Building Blocks (Level 3)</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Building Block</th>
                                <th>Count</th>
                                <th>Percentage</th>
                            </tr>
                            @php
                                $level3s = \App\Models\ref_level3::orderBy('level3_desc')->get();
                                $level3Counts = \App\Models\Level::whereIn('project_id', $filteredProjects->pluck('project_id'))
                                    ->whereNotNull('level3')
                                    ->select('level3', \DB::raw('COUNT(DISTINCT project_id) as count'))
                                    ->groupBy('level3')
                                    ->pluck('count', 'level3');
                                $totalProjectsForLevel3 = $filteredProjects->count();
                            @endphp
                            @foreach($level3s as $level3)
                                @php
                                    $count = $level3Counts[$level3->level3_desc] ?? 0;
                                    $percentage = $totalProjectsForLevel3 > 0 ? ($count / $totalProjectsForLevel3) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $level3->level3_desc }}</td>
                                    <td>{{ $count }}</td>
                                    <td>{{ number_format($percentage, 1) }}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>

                    <!-- 8. Financial Management -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Financial Management</h2>
                        <table class="summary-table">
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                            </tr>
                            <tr>
                                <td>Total Budget for {{ $filteredProjects->count() }} Projects</td>
                                <td>&#8369; {{ number_format(\App\Models\FinancialAccomplishment::sumLatestBudget(request('year')), 2, '.', ',') }}</td>
                            </tr>
                            <tr>
                                <td>Total Disbursement for {{ $filteredProjects->count() }} Projects</td>
                                <td>&#8369; {{ number_format(\App\Models\FinancialAccomplishment::sumLatestDisbursements(request('year')), 2, '.', ',') }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- 9: Total Budget by Funding Source -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Total Budget by Funding Source</h2>
                        @php
                            $budgetByFundingSource = \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSource(request('year'));
                            $totalBudgetForFundingSource = array_sum($budgetByFundingSource);
                            $allFundsForBudget = \App\Models\ref_funds::orderBy('funds_code')->get();
                        @endphp
                        <table class="summary-table">
                            <tr>
                                <th>Funding Source</th>
                                <th>Budget</th>
                                <th>Percentage</th>
                            </tr>
                            @foreach($allFundsForBudget as $fund)
                                @php
                                    $budget = $budgetByFundingSource[$fund->funds_desc] ?? 0;
                                    $percentage = $totalBudgetForFundingSource > 0 ? ($budget / $totalBudgetForFundingSource) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $fund->funds_code }} ({{ $fund->funds_desc }})</td>
                                    <td>₱{{ number_format($budget, 2) }}</td>
                                    <td>{{ number_format($percentage, 2) }}%</td>
                                </tr>
                            @endforeach
                            <tr class="table-info">
                                <td><strong>Total</strong></td>
                                <td colspan="2"><strong>₱{{ number_format($totalBudgetForFundingSource, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>

                    <!-- 10: Total Disbursement by Funding Source -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Total Disbursement by Funding Source</h2>
                        @php
                            $disbursementByFundingSource = \App\Models\FinancialAccomplishment::sumLatestDisbursementPerFundingSource(request('year'));
                            $totalDisbursementForFundingSource = array_sum($disbursementByFundingSource);
                            $allFundsForDisbursement = \App\Models\ref_funds::orderBy('funds_code')->get();
                        @endphp
                        <table class="summary-table">
                            <tr>
                                <th>Funding Source</th>
                                <th>Disbursement</th>
                                <th>Percentage</th>
                            </tr>
                            @foreach($allFundsForDisbursement as $fund)
                                @php
                                    $disbursement = $disbursementByFundingSource[$fund->funds_desc] ?? 0;
                                    $percentage = $totalDisbursementForFundingSource > 0 ? ($disbursement / $totalDisbursementForFundingSource) * 100 : 0;
                                @endphp
                                <tr>
                                    <td>{{ $fund->funds_code }} ({{ $fund->funds_desc }})</td>
                                    <td>₱{{ number_format($disbursement, 2) }}</td>
                                    <td>{{ number_format($percentage, 2) }}%</td>
                                </tr>
                            @endforeach
                            <tr class="table-info">
                                <td><strong>Total</strong></td>
                                <td colspan="2"><strong>₱{{ number_format($totalDisbursementForFundingSource, 2) }}</strong></td>
                            </tr>
                        </table>
                    </div>

                    <!-- 11. Physical Accomplishments -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Physical Accomplishments</h2>
                        @php
                            $physicalStats = \App\Models\PhysicalAccomplishment::getLatestStatsByYear(request('year'));
                        @endphp

                        <h3 style="color: #495057; margin-bottom: 8px; font-size: 14px; margin-top: 15px;">Infrastructure Projects</h3>
                        <table class="summary-table">
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                            </tr>
                            <tr>
                                <td>Behind Schedule</td>
                                <td>{{ $physicalStats['infra']['behind'] }}</td>
                            </tr>
                            <tr>
                                <td>On Time</td>
                                <td>{{ $physicalStats['infra']['on_time'] }}</td>
                            </tr>
                            <tr>
                                <td>Ahead</td>
                                <td>{{ $physicalStats['infra']['ahead'] }}</td>
                            </tr>
                            <tr class="table-info">
                                <td><strong>Total</strong></td>
                                <td><strong>{{ $physicalStats['total']['infra'] }}</strong></td>
                            </tr>
                        </table>

                        <h3 style="color: #495057; margin-bottom: 8px; font-size: 14px; margin-top: 15px;">Non-Infrastructure Projects</h3>
                        <table class="summary-table">
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                            </tr>
                            <tr>
                                <td>Behind Schedule</td>
                                <td>{{ $physicalStats['non_infra']['behind'] }}</td>
                            </tr>
                            <tr>
                                <td>On Time</td>
                                <td>{{ $physicalStats['non_infra']['on_time'] }}</td>
                            </tr>
                            <tr>
                                <td>Ahead</td>
                                <td>{{ $physicalStats['non_infra']['ahead'] }}</td>
                            </tr>
                            <tr class="table-info">
                                <td><strong>Total</strong></td>
                                <td><strong>{{ $physicalStats['total']['non_infra'] }}</strong></td>
                            </tr>
                        </table>

                        <h3 style="color: #495057; margin-bottom: 8px; font-size: 14px; margin-top: 15px;">Combined Projects</h3>
                        <table class="summary-table">
                            <tr>
                                <th>Status</th>
                                <th>Count</th>
                            </tr>
                            <tr>
                                <td>Behind Schedule</td>
                                <td>{{ $physicalStats['combined']['behind'] }}</td>
                            </tr>
                            <tr>
                                <td>On Time</td>
                                <td>{{ $physicalStats['combined']['on_time'] }}</td>
                            </tr>
                            <tr>
                                <td>Ahead</td>
                                <td>{{ $physicalStats['combined']['ahead'] }}</td>
                            </tr>
                            <tr class="table-info">
                                <td><strong>Total</strong></td>
                                <td><strong>{{ $physicalStats['total']['combined'] }}</strong></td>
                            </tr>
                        </table>
                    </div>

                    <!-- 12. Recent Projects -->
                    <!-- <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Recent Projects</h2>
                        @php
                            $recentProjects = \App\Models\Project::select('projects.project_id', 'projects.short_title', 'projects.status')
                                ->join('implementation_schedules', 'projects.project_id', '=', 'implementation_schedules.project_id')
                                ->whereRaw('implementation_schedules.start_date = (
                                    SELECT MAX(start_date)
                                    FROM implementation_schedules is2
                                    WHERE is2.project_id = projects.project_id
                                )')
                                ->orderByDesc('implementation_schedules.start_date')
                                ->limit(5)
                                ->get();
                        @endphp
                        <table class="summary-table">
                            <tr>
                                <th>Project Title</th>
                                <th>Status</th>
                            </tr>
                            @forelse($recentProjects as $project)
                                <tr>
                                    <td>{{ $project->short_title }}</td>
                                    <td>{{ ucfirst($project->status) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No projects found</td>
                                </tr>
                            @endforelse
                        </table>
                    </div> -->

                    <!-- 13. Charts and Visualizations -->
                    <div class="section bordered mb-4">
                        <h2 style="color: #007bff; margin-bottom: 12px; font-size: 16px; border-bottom: 1px solid #007bff; padding-bottom: 4px;">Charts and Visualizations</h2>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Project Trends</h3>
                            <div class="chart-container" style="width: 100%; height: 350px; margin: 15px 0;">
                                <canvas id="projectTrendChart" width="800" height="350"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Project Status Distribution</h3>
                            <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
                                <canvas id="statusChart" width="800" height="250"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Funding Source Distribution</h3>
                            <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
                                <canvas id="fundingChart" width="800" height="250"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Fund Type Distribution</h3>
                            <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
                                <canvas id="fundTypeChart" width="800" height="250"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">DePDev Classification</h3>
                            <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
                                <canvas id="depdevChart" width="800" height="250"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Management Types</h3>
                            <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
                                <canvas id="managementChart" width="800" height="250"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Geographical Distribution</h3>
                            <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
                                <canvas id="geoChart" width="800" height="250"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Projects per Region</h3>
                            <div class="chart-container" style="width: 100%; height: 300px; margin: 15px 0;">
                                <canvas id="regionChart" width="800" height="300"></canvas>
                            </div>
                        </div>

                        <!-- Health Area Line Chart -->
                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Health Area Distribution (Levels 1, 2, 3)</h3>
                            <div class="chart-container" style="width: 100%; height: 350px; margin: 15px 0;">
                                <canvas id="healthAreaLineChart" width="900" height="350"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Financial Management (Overall)</h3>
                            <div class="chart-container" style="width: 100%; height: 220px; margin: 15px 0;">
                                <canvas id="financeChart" width="400" height="220"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Total Budget by Funding Source</h3>
                            <div class="chart-container" style="width: 100%; height: 300px; margin: 15px 0;">
                                <canvas id="budgetByFundChart" width="800" height="300"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Total Disbursement by Funding Source</h3>
                            <div class="chart-container" style="width: 100%; height: 300px; margin: 15px 0;">
                                <canvas id="disbursementByFundChart" width="800" height="300"></canvas>
                            </div>
                        </div>

                        <div class="chart-section">
                            <h3 style="color: #495057; margin-bottom: 12px; font-size: 14px; text-align: center;">Physical Accomplishments</h3>
                            <div class="chart-container" style="width: 100%; height: 300px; margin: 15px 0;">
                                <canvas id="physicalChart" width="800" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="report-footer text-center mt-4 pt-3 border-top">
                        <em>FAPMES Dashboard Summary Report - {{ config('app.name', 'FAPMES') }}</em>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Print Styles -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .report-content, .report-content * {
                visibility: visible;
            }
            .report-content {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                padding: 20px;
                background: white;
            }
            .card-header {
                display: none;
            }
            .card-body {
                padding: 0;
            }
            .report-content {
                padding: 10px;
            }
            .section {
                margin-bottom: 15px;
                page-break-inside: avoid;
            }
            .section.bordered {
                padding: 10px;
            }
            .summary-table {
                font-size: 10px;
            }
            .summary-table th,
            .summary-table td {
                padding: 4px 6px;
            }
            .chart-section {
                margin-bottom: 20px;
                page-break-inside: avoid;
            }
            .chart-container {
                margin: 10px 0;
                page-break-inside: avoid;
            }
        }

        /* Report Styles */
        .report-content {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
        }

        .section.bordered {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 6px 10px;
            text-align: left;
        }

        .summary-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .summary-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .summary-table tr.table-info {
            background-color: #d1ecf1;
            font-weight: bold;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
            page-break-inside: avoid;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        @php
            // Prepare all data in PHP first
            $statusData = [
                'pipeline' => $filteredProjects->where('status', 'Pipeline')->count(),
                'active' => $filteredProjects->where('status', 'Active')->count(),
                'completed' => $filteredProjects->where('status', 'Completed')->count(),
            ];

            $allFundingSources = \App\Models\ref_funds::orderBy('funds_code')->get();
            $fundingLabels = $allFundingSources->pluck('funds_code');
            $fundingCounts = $allFundingSources->map(function($fund) use ($filteredProjects) {
                return $filteredProjects->where('funding_source', $fund->funds_desc)->count();
            });

            $fundTypes = ['Loan', 'Grants'];
            $fundTypeLabels = $fundTypes;
            $fundTypeCounts = collect($fundTypes)->map(function($type) use ($filteredProjects) {
                return $filteredProjects->where('fund_type', $type)->count();
            });

            $allSites = ['Multi-Regional', 'Nationwide', 'Region-specific', 'DOH - Central Office'];
            $geoCounts = collect($allSites)->map(function($site) use ($filteredProjects) {
                return $filteredProjects->where('sites', $site)->count();
            });

            $fundingSources = \App\Models\ref_funds::orderBy('funds_code')->pluck('funds_code');
            $fundingSourceDescs = \App\Models\ref_funds::orderBy('funds_code')->pluck('funds_desc', 'funds_code');
            $budgetDataAssoc = \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSource(request('year'));

            $projectCounts = [];
            $budgetData = [];
            foreach ($fundingSources as $code) {
                $desc = $fundingSourceDescs[$code] ?? '';
                $projectCounts[] = $filteredProjects->where('funding_source', $desc)->count();
                $budgetData[] = isset($budgetDataAssoc[$desc]) ? round((float) $budgetDataAssoc[$desc], 2) : 0.00;
            }

            $regions = \App\Models\ref_region::orderBy('nscb_reg_name')->get();
            $regionLabels = $regions->pluck('nscb_reg_name');
            $regionCounts = $regions->map(function($region) {
                return \App\Models\Project::getProjectCountByRegion($region, request('year'));
            });

            $financeData = [
                \App\Models\FinancialAccomplishment::sumLatestBudget(request('year')),
                \App\Models\FinancialAccomplishment::sumLatestDisbursements(request('year')),
            ];

            $physicalStats = \App\Models\PhysicalAccomplishment::getLatestStatsByYear(request('year'));
            $physicalData = [
                'behind' => [
                    $physicalStats['infra']['behind'],
                    $physicalStats['non_infra']['behind'],
                    $physicalStats['combined']['behind'],
                ],
                'on_time' => [
                    $physicalStats['infra']['on_time'],
                    $physicalStats['non_infra']['on_time'],
                    $physicalStats['combined']['on_time'],
                ],
                'ahead' => [
                    $physicalStats['infra']['ahead'],
                    $physicalStats['non_infra']['ahead'],
                    $physicalStats['combined']['ahead'],
                ],
            ];

            $depdevs = \App\Models\ref_depdev::all();
            $depdevLabels = $depdevs->pluck('depdev_desc');
            $depdevCounts = $depdevs->map(function($depdev) use ($filteredProjects) {
                return $filteredProjects->where('depdev', $depdev->depdev_desc)->count();
            });

            $managements = \App\Models\ref_management::all();
            $managementLabels = $managements->pluck('management_desc');
            $managementCounts = $managements->map(function($management) use ($filteredProjects) {
                return $filteredProjects->where('management', $management->management_desc)->count();
            });

            $budgetByFundingSource = \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSource(request('year'));
            $disbursementByFundingSource = \App\Models\FinancialAccomplishment::sumLatestDisbursementPerFundingSource(request('year'));

            $budgetByFundLabels = $allFundingSources->pluck('funds_code');
            $budgetByFundData = $allFundingSources->map(function($fund) use ($budgetByFundingSource) {
                return $budgetByFundingSource[$fund->funds_desc] ?? 0;
            });

            $disbursementByFundLabels = $allFundingSources->pluck('funds_code');
            $disbursementByFundData = $allFundingSources->map(function($fund) use ($disbursementByFundingSource) {
                return $disbursementByFundingSource[$fund->funds_desc] ?? 0;
            });

            // Health Area (Level 1)
            $level1s = \App\Models\ref_level1::orderBy('level1_desc')->get();
            $level1Counts = \App\Models\Level::whereIn('project_id', $filteredProjects->pluck('project_id'))
                ->whereNotNull('level1')
                ->select('level1', \DB::raw('COUNT(DISTINCT project_id) as count'))
                ->groupBy('level1')
                ->pluck('count', 'level1');
            $healthAreaLabels1 = $level1s->pluck('level1_desc');
            $healthAreaCounts1 = $level1s->map(function($level1) use ($level1Counts) {
                return $level1Counts[$level1->level1_desc] ?? 0;
            });
            // Health Area (Level 2)
            $level2s = \App\Models\ref_level2::orderBy('level2_desc')->get();
            $level2Counts = \App\Models\Level::whereIn('project_id', $filteredProjects->pluck('project_id'))
                ->whereNotNull('level2')
                ->select('level2', \DB::raw('COUNT(DISTINCT project_id) as count'))
                ->groupBy('level2')
                ->pluck('count', 'level2');
            $healthAreaLabels2 = $level2s->pluck('level2_desc');
            $healthAreaCounts2 = $level2s->map(function($level2) use ($level2Counts) {
                return $level2Counts[$level2->level2_desc] ?? 0;
            });
            // Health Area (Level 3)
            $level3s = \App\Models\ref_level3::orderBy('level3_desc')->get();
            $level3Counts = \App\Models\Level::whereIn('project_id', $filteredProjects->pluck('project_id'))
                ->whereNotNull('level3')
                ->select('level3', \DB::raw('COUNT(DISTINCT project_id) as count'))
                ->groupBy('level3')
                ->pluck('count', 'level3');
            $healthAreaLabels3 = $level3s->pluck('level3_desc');
            $healthAreaCounts3 = $level3s->map(function($level3) use ($level3Counts) {
                return $level3Counts[$level3->level3_desc] ?? 0;
            });
        @endphp

        // Prepare data for charts using JSON encoding
        const statusData = {!! json_encode($statusData) !!};
        const fundingLabels = {!! json_encode($fundingLabels) !!};
        const fundingCounts = {!! json_encode($fundingCounts) !!};
        const fundTypeLabels = {!! json_encode($fundTypeLabels) !!};
        const fundTypeCounts = {!! json_encode($fundTypeCounts) !!};
        const geoLabels = {!! json_encode($allSites) !!};
        const geoCounts = {!! json_encode($geoCounts) !!};
        const fundingSources = {!! json_encode($fundingSources) !!};
        const projectCounts = {!! json_encode($projectCounts) !!};
        const budgetData = {!! json_encode($budgetData) !!};
        const regionLabels = {!! json_encode($regionLabels) !!};
        const regionCounts = {!! json_encode($regionCounts) !!};
        const financeLabels = ['Total Budget', 'Total Disbursement'];
        const financeData = {!! json_encode($financeData) !!};
        const physicalLabels = ['Infrastructure', 'Non-Infrastructure', 'Combined'];
        const physicalData = {!! json_encode($physicalData) !!};
        const depdevLabels = {!! json_encode($depdevLabels) !!};
        const depdevCounts = {!! json_encode($depdevCounts) !!};
        const managementLabels = {!! json_encode($managementLabels) !!};
        const managementCounts = {!! json_encode($managementCounts) !!};
        const budgetByFundLabels = {!! json_encode($budgetByFundLabels) !!};
        const budgetByFundData = {!! json_encode($budgetByFundData) !!};
        const disbursementByFundLabels = {!! json_encode($disbursementByFundLabels) !!};
        const disbursementByFundData = {!! json_encode($disbursementByFundData) !!};
        const healthAreaLabels1 = {!! json_encode($healthAreaLabels1) !!};
        const healthAreaCounts1 = {!! json_encode($healthAreaCounts1) !!};
        const healthAreaLabels2 = {!! json_encode($healthAreaLabels2) !!};
        const healthAreaCounts2 = {!! json_encode($healthAreaCounts2) !!};
        const healthAreaLabels3 = {!! json_encode($healthAreaLabels3) !!};
        const healthAreaCounts3 = {!! json_encode($healthAreaCounts3) !!};

        function formatCurrency2(value) {
            return value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        // Project Trends Chart - Simplified version
        const projectTrendCtx = document.getElementById('projectTrendChart').getContext('2d');
        new Chart(projectTrendCtx, {
            type: 'line',
            data: {
                labels: fundingSources,
                datasets: [
                    {
                        label: 'Total Projects Count',
                        data: projectCounts,
                        borderColor: '#6B48FF',
                        backgroundColor: 'rgba(107, 72, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Latest Total Budget',
                        data: budgetData,
                        borderColor: '#FF9800',
                        backgroundColor: 'rgba(255, 152, 0, 0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.4,
                        yAxisID: 'y1',
                        type: 'line',
                        pointStyle: 'rectRot',
                        pointRadius: 4,
                        pointBackgroundColor: '#FF9800',
                        datalabels: {
                            display: true,
                            color: '#FF9800',
                            anchor: 'end',
                            align: 'top',
                            offset: 5,
                            formatter: function(value, context) {
                                if (context.datasetIndex === 1) {
                                    if (value && !isNaN(value)) {
                                        return '₱' + formatCurrency2(value);
                                    }
                                }
                                return '';
                            },
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        top: 20
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Project Count'
                        }
                    },
                    y1: {
                        beginAtZero: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false
                        },
                        title: {
                            display: true,
                            text: 'Latest Total Budget'
                        },
                        ticks: {
                            callback: function(value) {
                                return '₱' + formatCurrency2(value);
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    title: {
                        display: true,
                        text: 'Projects Overview',
                        position: 'top',
                        padding: {
                            top: 5,
                            bottom: 15
                        },
                        font: {
                            size: 14,
                            weight: 'bold'
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        offset: 5,
                        formatter: function(value, context) {
                            if (context.datasetIndex === 0) {
                                return value;
                            }
                            return '';
                        },
                        font: {
                            weight: 'bold',
                            size: 12
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 1) {
                                    return context.dataset.label + ': ₱' + formatCurrency2(context.parsed.y);
                                }
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Project Status Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'pie',
            data: {
                labels: ['Pipeline', 'Active', 'Completed'],
                datasets: [{
                    data: [statusData.pipeline, statusData.active, statusData.completed],
                    backgroundColor: ['#6c757d', '#007bff', '#28a745'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Project Status Distribution',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Funding Source Chart
        const fundingCtx = document.getElementById('fundingChart').getContext('2d');
        new Chart(fundingCtx, {
            type: 'bar',
            data: {
                labels: fundingLabels,
                datasets: [{
                    label: 'Project Count',
                    data: fundingCounts,
                    backgroundColor: [
                        '#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1',
                        '#fd7e14', '#20c997', '#e83e8c', '#6c757d', '#198754'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Projects by Funding Source',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 12
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Fund Types Chart
        const fundTypeCtx = document.getElementById('fundTypeChart').getContext('2d');
        new Chart(fundTypeCtx, {
            type: 'pie',
            data: {
                labels: fundTypeLabels,
                datasets: [{
                    data: fundTypeCounts,
                    backgroundColor: ['#0d6efd', '#198754'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    title: {
                        display: true,
                        text: 'Fund Type Distribution',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Geographical Distribution Chart
        const geoCtx = document.getElementById('geoChart').getContext('2d');
        new Chart(geoCtx, {
            type: 'doughnut',
            data: {
                labels: geoLabels,
                datasets: [{
                    label: 'Project Count',
                    data: geoCounts,
                    backgroundColor: [
                        '#17a2b8', '#ffc107', '#28a745', '#dc3545'
                    ],
                    borderWidth: 1,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Geographical Distribution',
                        font: {
                            size: 16,
                            weight: 'bold'
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 13
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Projects per Region Chart
        const regionCtx = document.getElementById('regionChart').getContext('2d');
        new Chart(regionCtx, {
            type: 'bar',
            data: {
                labels: regionLabels,
                datasets: [{
                    label: 'Project Count',
                    data: regionCounts,
                    backgroundColor: '#1976d2',
                    borderColor: '#1565c0',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Projects per Region',
                        font: { size: 16, weight: 'bold' }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Financial Management Chart (Overall)
        const financeCtx = document.getElementById('financeChart').getContext('2d');
        new Chart(financeCtx, {
            type: 'bar',
            data: {
                labels: financeLabels,
                datasets: [{
                    label: 'Amount (₱)',
                    data: financeData,
                    backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(25, 118, 210, 0.7)'],
                    borderColor: ['rgba(40, 167, 69, 1)', 'rgba(25, 118, 210, 1)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Financial Management (Overall)',
                        font: { size: 16, weight: 'bold' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {
                            return '₱' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Budget by Funding Source Chart
        const budgetByFundCtx = document.getElementById('budgetByFundChart').getContext('2d');
        new Chart(budgetByFundCtx, {
            type: 'bar',
            data: {
                labels: budgetByFundLabels,
                datasets: [{
                    label: 'Total Budget (₱)',
                    data: budgetByFundData,
                    backgroundColor: '#0d1a4a',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Total Budget by Funding Source',
                        font: { size: 16, weight: 'bold' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value, context) {
                            if (value > 0) {
                                return '₱' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                            return '';
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Disbursement by Funding Source Chart
        const disbursementByFundCtx = document.getElementById('disbursementByFundChart').getContext('2d');
        new Chart(disbursementByFundCtx, {
            type: 'bar',
            data: {
                labels: disbursementByFundLabels,
                datasets: [{
                    label: 'Total Disbursement (₱)',
                    data: disbursementByFundData,
                    backgroundColor: '#1976d2',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Total Disbursement by Funding Source',
                        font: { size: 16, weight: 'bold' }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ₱' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value, context) {
                            if (value > 0) {
                                return '₱' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                            return '';
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Physical Accomplishments Chart
        const physicalCtx = document.getElementById('physicalChart').getContext('2d');
        new Chart(physicalCtx, {
            type: 'bar',
            data: {
                labels: physicalLabels,
                datasets: [
                    {
                        label: 'Behind Schedule',
                        data: physicalData.behind,
                        backgroundColor: 'rgba(255, 193, 7, 0.8)'
                    },
                    {
                        label: 'On Time',
                        data: physicalData.on_time,
                        backgroundColor: 'rgba(40, 167, 69, 0.8)'
                    },
                    {
                        label: 'Ahead',
                        data: physicalData.ahead,
                        backgroundColor: 'rgba(23, 162, 184, 0.8)'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Physical Accomplishments',
                        font: { size: 16, weight: 'bold' }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 11
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            },
            plugins: [ChartDataLabels]
        });

        // DePDev Classification Chart
        const depdevCtx = document.getElementById('depdevChart').getContext('2d');
        new Chart(depdevCtx, {
            type: 'pie',
            data: {
                labels: depdevLabels,
                datasets: [{
                    data: depdevCounts,
                    backgroundColor: ['#3498db', '#f1c40f', '#e74c3c', '#2ecc71'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'DePDev Classification',
                        font: { size: 16, weight: 'bold' }
                    },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Management Types Chart
        const managementCtx = document.getElementById('managementChart').getContext('2d');
        new Chart(managementCtx, {
            type: 'pie',
            data: {
                labels: managementLabels,
                datasets: [{
                    data: managementCounts,
                    backgroundColor: ['#FFC107', '#00B8D9', '#FF5252', '#4CAF50'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' },
                    title: {
                        display: true,
                        text: 'Management Types',
                        font: { size: 16, weight: 'bold' }
                    },
                    datalabels: {
                        display: true,
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 14
                        },
                        formatter: function(value, context) {
                            return value;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });

        // Health Area Distribution Line Chart (Levels 1, 2, 3)
        const healthAreaLineCtx = document.getElementById('healthAreaLineChart').getContext('2d');
        new Chart(healthAreaLineCtx, {
            type: 'line',
            data: {
                labels: [
                    ...healthAreaLabels1,
                    ...healthAreaLabels2,
                    ...healthAreaLabels3
                ],
                datasets: [
                    {
                        label: 'Level 1',
                        data: [...healthAreaCounts1, ...Array(healthAreaLabels2.length + healthAreaLabels3.length).fill(null)],
                        borderColor: '#007bff',
                        backgroundColor: 'rgba(0,123,255,0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Level 2',
                        data: [
                            ...Array(healthAreaLabels1.length).fill(null),
                            ...healthAreaCounts2,
                            ...Array(healthAreaLabels3.length).fill(null)
                        ],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40,167,69,0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    },
                    {
                        label: 'Level 3',
                        data: [
                            ...Array(healthAreaLabels1.length + healthAreaLabels2.length).fill(null),
                            ...healthAreaCounts3
                        ],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220,53,69,0.1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.3
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' },
                    title: {
                        display: true,
                        text: 'Health Area Distribution (Levels 1, 2, 3)',
                        font: { size: 16, weight: 'bold' }
                    },
                    datalabels: {
                        display: true,
                        color: '#000',
                        anchor: 'end',
                        align: 'top',
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        formatter: function(value, context) {
                            if (value !== null && value > 0) {
                                return value;
                            }
                            return '';
                        }
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Health Area Categories'
                        },
                        ticks: {
                            autoSkip: false,
                            maxRotation: 60,
                            minRotation: 30,
                            font: { size: 10 }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Project Count'
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    });
    </script>
</x-app-layout>
