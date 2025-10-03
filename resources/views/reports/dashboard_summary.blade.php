<x-report-layout>
<div class="report-header">
    <h1>Dashboard Summary Report</h1>
    <p class="text-muted small mb-2">Year: <strong>{{ request('year') ?? 'All' }}</strong> | Generated: {{ date('Y-m-d H:i') }}</p>
</div>

<!-- 1. DOH Portfolio (Project Portfolio Overview) -->
<div class="section bordered">
    <h2>DOH Portfolio Overview</h2>
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
<div class="section bordered">
    <h2>Projects by Funding Source and Status</h2>
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
<div class="section bordered">
    <h2>Projects by Fund Type</h2>
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

<!-- 4. Geographical Distribution -->
<div class="section bordered">
    <h2>Geographical Distribution</h2>
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

<!-- 5. Projects per Region -->
<div class="section bordered">
    <h2>Projects per Region</h2>
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

<!-- 6. Financial Management -->
<div class="section bordered">
    <h2>Financial Management</h2>
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

<!-- New: Total Budget by Funding Source -->
<div class="section bordered">
    <h2>Total Budget by Funding Source</h2>
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

<!-- New: Total Disbursement by Funding Source -->
<div class="section bordered">
    <h2>Total Disbursement by Funding Source</h2>
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

<!-- 7. Physical Accomplishments -->
<div class="section bordered">
    <h2>Physical Accomplishments</h2>
    @php
        $physicalStats = \App\Models\PhysicalAccomplishment::getLatestStatsByYear(request('year'));
    @endphp

    <h3>Infrastructure Projects</h3>
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

    <h3>Non-Infrastructure Projects</h3>
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

    <h3>Combined Projects</h3>
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

<!-- 8. Recent Projects -->
<div class="section bordered">
    <h2>Recent Projects</h2>
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
</div>

<!-- 9. Charts and Visualizations -->
<div class="section bordered">
    <h2>Charts and Visualizations</h2>

    <div class="chart-section">
        <h3>Project Trends</h3>
        <div class="chart-container" style="width: 100%; height: 350px; margin: 15px 0;">
            <canvas id="projectTrendChart" width="800" height="350"></canvas>
        </div>
    </div>

    <div class="chart-section">
        <h3>Project Status Distribution</h3>
        <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
            <canvas id="statusChart" width="800" height="250"></canvas>
        </div>
    </div>

    <div class="chart-section">
        <h3>Funding Source Distribution</h3>
        <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
            <canvas id="fundingChart" width="800" height="250"></canvas>
        </div>
    </div>

    <div class="chart-section">
        <h3>Geographical Distribution</h3>
        <div class="chart-container" style="width: 100%; height: 250px; margin: 15px 0;">
            <canvas id="geoChart" width="800" height="250"></canvas>
        </div>
    </div>
</div>

<div class="report-footer">
    <em>FAPMES Dashboard Summary Report - {{ config('app.name', 'FAPMES') }}</em>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prepare data for charts using JSON encoding
    const statusData = {
        pipeline: {{ $filteredProjects->where('status', 'Pipeline')->count() }},
        active: {{ $filteredProjects->where('status', 'Active')->count() }},
        completed: {{ $filteredProjects->where('status', 'Completed')->count() }}
    };

    // Get all funding sources and their counts (including 0 counts)
    @php
        $allFundingSources = \App\Models\ref_funds::orderBy('funds_code')->get();
        $fundingLabels = [];
        $fundingCounts = [];
        foreach ($allFundingSources as $fund) {
            $fundingLabels[] = $fund->funds_code;
            $fundingCounts[] = $filteredProjects->where('funding_source', $fund->funds_desc)->count();
        }
    @endphp
    const fundingLabels = {!! json_encode($fundingLabels) !!};
    const fundingCounts = {!! json_encode($fundingCounts) !!};

    // Get all sites and their counts (including 0 counts)
    @php
        $allSites = ['Multi-Regional', 'Nationwide', 'Region-specific', 'DOH - Central Office'];
        $geoLabels = [];
        $geoCounts = [];
        foreach ($allSites as $site) {
            $geoLabels[] = $site;
            $geoCounts[] = $filteredProjects->where('sites', $site)->count();
        }
    @endphp
    const geoLabels = {!! json_encode($geoLabels) !!};
    const geoCounts = {!! json_encode($geoCounts) !!};

    // Project Trends Chart Data - Simplified without filters
    @php
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
    @endphp

    const fundingSources = {!! json_encode($fundingSources) !!};
    const projectCounts = {!! json_encode($projectCounts) !!};
    const budgetData = {!! json_encode($budgetData) !!};

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
                                    return '&#8369;' + formatCurrency2(value);
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
                            return '&#8369;' + formatCurrency2(value);
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
                                return context.dataset.label + ': &#8369;' + formatCurrency2(context.parsed.y);
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
                }
            }
        }
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
        }
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
                }
            }
        }
    });
});
</script>
</x-report-layout>
