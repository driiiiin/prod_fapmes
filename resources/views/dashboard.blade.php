<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <div class="container-fluid m-4 p-4">
    <marquee id="dashboardNoticeMarquee" behavior="scroll" direction="left" scrollamount="10" style="color: #b71c1c; font-weight: bold; font-size: 1.1rem; background:#FAF9F6; padding: 6px 0;">
        Note: The data generated in this dashboard is for the year 2024 onwards only.
    </marquee>
    <script>
        // When the marquee finishes scrolling, hide it
        document.addEventListener('DOMContentLoaded', function() {
            var marquee = document.getElementById('dashboardNoticeMarquee');
            // Calculate duration based on scrollamount and width
            var scrollAmount = parseInt(marquee.getAttribute('scrollamount')) || 10;
            var marqueeWidth = marquee.offsetWidth;
            var parentWidth = marquee.parentElement.offsetWidth || window.innerWidth;
            // Estimate time: (marqueeWidth + parentWidth) / scrollAmount * 10 (ms per px at scrollamount=1)
            var duration = ((marqueeWidth + parentWidth) / scrollAmount) * 57;
            setTimeout(function() {
                marquee.style.display = 'none';
            }, duration);
        });
    </script>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title d-flex justify-content-between align-items-center mb-0">
                    <span style="font-size: 1.25rem; font-weight: 500;">Dashboard</span>
                    @if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
                    <a href="{{ route('dashboard.summary.preview') }}" class="btn btn-primary btn-sm ms-3">
                        <i class="fas fa-file-alt me-1"></i> Generate Report
                    </a>
                    @endif
                    <!-- <div class="d-flex align-items-center">
                        <form id="yearFilterForm" method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center me-3">
                            <label for="yearFilter" class="me-2 mb-0">Filter by Year:</label>
                            <select name="year" id="yearFilter" class="form-select form-select-sm" style="width: auto;" onchange="document.getElementById('yearFilterForm').submit()">
                                <option value="" {{ request('year') ? '' : 'selected' }}>All</option>
                                @php
                                    $years = \App\Models\Project::whereNotNull('completed_date')
                                        ->selectRaw('YEAR(completed_date) as year')
                                        ->distinct()
                                        ->orderByDesc('year')
                                        ->pluck('year');
                                @endphp
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div> -->
                </h5>
            </div>

            <div class="card-body">
                @php
                    $filterYear = request('year');
                    // Only completed projects for the selected year, or all if not filtered
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
                @endphp

                <div class="row g-4 mb-4 d-flex flex-wrap">
                    @if (auth()->user()->userlevel == -1 || auth()->user()->userlevel == 2)
                    <div class="col-md-3 d-flex">
                        <div class="card border-0 shadow-sm bg-primary w-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="card-title fw-semibold text-white mb-0"><i class="fa fa-users fa-fw" aria-hidden="true"></i> User Management</h5>
                                </div>

                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <div>
                                        <h2 class="display-6 fw-bold text-white mb-0">{{ \App\Models\User::count() }}</h2>
                                        <small class="text-white"> <a href="{{ route('useraccount.index') }}" class="text-white">Total Users</a></small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-gray bg-opacity-10 text-gray">{{ \App\Models\User::where('is_approved', 1)->count() }} Approved</span>
                                        <span class="badge bg-gray bg-opacity-10 text-warning ms-1">{{ \App\Models\User::where('is_approved', 0)->count() }} Pending</span>
                                    </div>
                                </div>

                                <div class="progress mb-3" style="height: 6px;">
                                    @php
                                    $totalUsers = \App\Models\User::count();
                                    $approvedPercentage = $totalUsers > 0 ? (\App\Models\User::where('is_approved', 1)->count() / $totalUsers) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-secondary" role="progressbar" style="width: {{ $approvedPercentage }}%" aria-valuenow="{{ $approvedPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <div class="row g-2 mt-auto">
                                    <div class="col-6">
                                        <div class="p-2 rounded bg-light">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-circle-check text-success me-2"></i>
                                                <small class="text-muted"><strong style="font-size: 1.2rem;">{{ \App\Models\User::where('is_active', 1)->count() }}</strong> <br> Active</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 rounded bg-light">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-circle-pause text-secondary me-2"></i>
                                                <small class="text-muted"><strong style="font-size: 1.2rem;">{{ \App\Models\User::where('is_active', 0)->count() }}</strong> <br> Inactive</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="col-md-3 d-flex">
                        <div class="card border-0 shadow-sm w-100" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);" id="dohPortfolioCard">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <h5 class="card-title fw-semibold text-white mb-0">
                                        <i class="fa fa-list-alt fa-fw me-2"></i>
                                        <span style="cursor: pointer;" onclick="toggleDashboardCards()">DOH Portfolio</span>
                                        <i class="fa fa-chevron-down ms-auto" id="toggleIcon" style="transition: transform 0.3s ease; cursor: pointer;" onclick="toggleDashboardCards()"></i>
                                    </h5>
                                </div>

                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <div>
                                        <h2 class="display-6 fw-bold text-white mb-0">{{ $totalProjects }}</h2>
                                        <small class="text-white cursor-pointer"><a href="{{ route('fapslist', ['year' => $filterYear]) }}">Total Projects</a></small>
                                    </div>
                                </div>

                                <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.2);">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $pipelinePercentage }}%; background: rgba(255,255,255,0.9);" aria-valuenow="{{ $pipelinePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div class="progress-bar" role="progressbar" style="width: {{ $activePercentage }}%; background: rgba(255,255,255,0.9);" aria-valuenow="{{ $activePercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    <div class="progress-bar" role="progressbar" style="width: {{ $completedPercentage }}%; background: rgba(255,255,255,0.9);" aria-valuenow="{{ $completedPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <div class="row g-2 mt-auto">
                                    <div class="col-4">
                                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.1);">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-circle-check text-white me-2"></i>
                                                <small class="text-white"><strong style="font-size: 1.2rem; cursor:pointer;" class="portfolio-count-link" data-status="Pipeline">{{ $pipelineCount }}</strong> <br> Pipeline</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.1);">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-circle-pause text-white me-2"></i>
                                                <small class="text-white"><strong style="font-size: 1.2rem; cursor:pointer;" class="portfolio-count-link" data-status="Active">{{ $activeCount }}</strong> <br> Active</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="p-2 rounded" style="background: rgba(255,255,255,0.1);">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-circle-check text-white me-2"></i>
                                                <small class="text-white"><strong style="font-size: 1.2rem; cursor:pointer;" class="portfolio-count-link" data-status="Completed">{{ $completedCount }}</strong> <br> Completed</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 flex-column d-none" id="fundingSources">
                        <div class="card shadow-lg border-0 w-100" style="background: linear-gradient(135deg, #6B48FF, #1E002B);">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2 mt-3">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.2)">
                                            <i class="fas fa-coins text-warning"></i>
                                        </div>
                                        <h5 class="card-title fw-bold text-white mb-0">Projects by Funding Source and Status</h5>
                                    </div>
                                </div>

                                <div class="funding-sources flex-grow-1" style="height: 180px; overflow-y: auto; margin-top: 22px; padding-right: 15px;">
                                    @foreach(\App\Models\ref_funds::orderByDesc(
                                        \App\Models\Project::selectRaw('count(*)')
                                            ->whereColumn('funding_source', 'ref_funds.funds_desc')
                                    )->get() as $fund)
                                    @php
                                        $fundProjects = $filteredProjects->where('funding_source', $fund->funds_desc);
                                    @endphp
                                    <div class="card bg-light text-dark mb-3 mt-4">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="card-title fw-bold mb-0">{{ $fund->funds_code }}</h6>
                                                <span class="badge bg-light text-dark ms-auto funding-count-link" style="font-size: 1.1rem; padding: 2px 8px; border-radius: 4px; cursor:pointer;"
                                                    data-fund="{{ $fund->funds_desc }}" data-status="all">
                                                    {{ $fundProjects->count() }}
                                                </span>
                                            </div>
                                            <div class="bg-light rounded p-2">
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small class="text-muted">Pipeline:</small>
                                                    <span class="badge bg-secondary funding-count-link" style="cursor:pointer;"
                                                        data-fund="{{ $fund->funds_desc }}" data-status="Pipeline">
                                                        {{ $fundProjects->where('status', 'Pipeline')->count() }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between mb-1">
                                                    <small class="text-muted">Active:</small>
                                                    <span class="badge bg-primary funding-count-link" style="cursor:pointer;"
                                                        data-fund="{{ $fund->funds_desc }}" data-status="Active">
                                                        {{ $fundProjects->where('status', 'Active')->count() }}
                                                    </span>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <small class="text-muted">Completed:</small>
                                                    <span class="badge bg-success funding-count-link" style="cursor:pointer;"
                                                        data-fund="{{ $fund->funds_desc }}" data-status="Completed">
                                                        {{ $fundProjects->where('status', 'Completed')->count() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="fundTypesCard">
                        <div class="card border-0 shadow-sm bg-info w-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title fw-semibold mb-0"><i class="fa fa-money fa-fw me-2"></i>Fund Types</h5>
                                    <div style="width: 120px; height: 60px; background: rgba(33, 37, 41, 0.9); padding: 10px; border-radius: 4px;">
                                        <canvas id="fundTypeChart"></canvas>
                                    </div>
                                </div>

                                @php
                                $fundTypes = [
                                    'Loan' => ['icon' => 'university', 'color' => '#0d6efd'],
                                    'Grant' => ['icon' => 'gift', 'color' => '#198754']
                                ];
                                @endphp

                                <div class="row g-4 mt-auto">
                                    <div class="col-12">
                                        <div class="p-3 rounded bg-light">
                                            @foreach($fundTypes as $type => $config)
                                            @php
                                                $count = $filteredProjects->where('fund_type', $type)->count();
                                                $percentage = $totalProjects > 0 ? ($count / $totalProjects) * 100 : 0;
                                            @endphp
                                            <div class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'mb-3' : '' }}">
                                                <div>
                                                    <i class="fa fa-{{ $config['icon'] }}" style="font-size: 1.2rem; color: {{ $config['color'] }}"></i>
                                                    <small class="text-muted">{{ $type }}</small>
                                                </div>
                                                <div>
                                                    <strong style="font-size: 1.2rem; cursor:pointer;" class="fund-type-count-link" data-type="{{ $type }}">{{ $count }}</strong>
                                                    <span class="badge text-white ms-1" style="background-color: {{ $config['color'] }}">
                                                        {{ number_format($percentage, 1) }}%
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="progress {{ !$loop->last ? 'mb-3' : '' }}" style="height: 6px;">
                                                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $config['color'] }}" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ctx = document.getElementById('fundTypeChart').getContext('2d');
                                    new Chart(ctx, {
                                        type: 'pie',
                                        data: {
                                            labels: ['Loan', 'Grant'],
                                            datasets: [{
                                                data: [
                                                    {{ $filteredProjects->where('fund_type', 'Loan')->count() }},
                                                    {{ $filteredProjects->where('fund_type', 'Grant')->count() }}
                                                ],
                                                backgroundColor: [
                                                    '#0d6efd',
                                                    '#198754'
                                                ],
                                                borderColor: 'rgba(255, 255, 255, 0.8)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    enabled: false
                                                },
                                                datalabels: {
                                                    display: true,
                                                    color: '#fff',
                                                    font: {
                                                        weight: 'bold',
                                                        size: 12
                                                    },
                                                    formatter: function(value, context) {
                                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                        const percentage = ((value / total) * 100).toFixed(1);
                                                        return `${value}\n(${percentage}%)`;
                                                    }
                                                }
                                            }
                                        }
                                    });
                                });
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="depdevCard">
                        <div class="card border-0 shadow-sm w-100" style="background: linear-gradient(135deg, #E91E63 0%, #F06292 100%);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title fw-semibold mb-0">DePDev Classification</h5>
                                    <div class="chart-container" style="width: 80px; height: 40px;">
                                        <canvas id="miniBarChartDepDev"></canvas>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="p-3 rounded bg-light">
                                            @php
                                                $colors = ['#3498db', '#f1c40f'];
                                                $count = 0;
                                                $dataCounts = [];
                                            @endphp
                                            @foreach(DB::table('ref_depdev')->select('depdev_code', 'depdev_desc')->get() as $depdev)
                                                @php
                                                    $projectCount = $filteredProjects->where('depdev', $depdev->depdev_desc)->count();
                                                    $dataCounts[] = $projectCount;
                                                    $count += 1;
                                                    $percentage = $totalProjects > 0 ? ($projectCount / $totalProjects) * 100 : 0;
                                                @endphp
                                                <div class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'mb-3' : '' }}">
                                                    <div style="flex: 1; min-width: 0; margin-right: 8px;">
                                                        <i class="fa fa-circle" style="color: {{ $colors[$count % count($colors)] }}"></i>
                                                        <small class="text-muted" style="word-break: break-word; overflow-wrap: break-word;">{{ $depdev->depdev_desc }}</small>
                                                    </div>
                                                    <div style="flex-shrink: 0; white-space: nowrap;">
                                                        <strong style="font-size: 1.2rem; cursor:pointer;" class="depdev-count-link" data-depdev="{{ $depdev->depdev_desc }}">{{ $projectCount }}</strong>
                                                        <span class="badge text-white ms-1" style="background-color: {{ $colors[$count % count($colors)] }}">{{ number_format($percentage, 1) }}%</span>
                                                    </div>
                                                </div>
                                                <div class="progress {{ !$loop->last ? 'mb-3' : '' }}" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $colors[$count % count($colors)] }}" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="managementCard">
                        <div class="card border-0 shadow-lg w-100" style="background-color: #708238;">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title fw-semibold mb-0 text-white">Management Types</h5>
                                    <div class="chart-container" style="width: 80px; height: 40px;">
                                        <canvas id="miniBarChartManagement"></canvas>
                                    </div>
                                </div>
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="p-3 rounded bg-light">
                                            @php
                                                $colors = ['#FFC107', '#00B8D9', '#FF5252'];
                                                $count = 0;
                                                $dataCounts = [];
                                            @endphp
                                            @foreach(DB::table('ref_management')->select('management_code', 'management_desc')->get() as $management)
                                                @php
                                                    $projectCount = $filteredProjects->where('management', $management->management_desc)->count();
                                                    $dataCounts[] = $projectCount;
                                                    $count += 1;
                                                    $percentage = $totalProjects > 0 ? ($projectCount / $totalProjects) * 100 : 0;
                                                @endphp
                                                <div class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'mb-3' : '' }}">
                                                    <div>
                                                        <i class="fa fa-circle" style="color: {{ $colors[$count % count($colors)] }}"></i>
                                                        <small class="text-muted">{{ $management->management_desc }}</small>
                                                    </div>
                                                    <div>
                                                        <strong style="font-size: 1.2rem; cursor:pointer;" class="management-count-link" data-management="{{ $management->management_desc }}">{{ $projectCount }}</strong>
                                                        <span class="badge text-white ms-1" style="background-color: {{ $colors[$count % count($colors)] }}">{{ number_format($percentage, 1) }}%</span>
                                                    </div>
                                                </div>
                                                <div class="progress {{ !$loop->last ? 'mb-3' : '' }}" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $colors[$count % count($colors)] }}" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="geoDistributionCard">
                        <div class="card border-0 shadow-sm bg-warning bg-opacity-75 w-100">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title fw-semibold mb-0"><i class="fa fa-map-marker fa-fw me-2"></i>Geographical Distribution</h5>
                                    <div style="width: 120px; height: 60px; background: rgba(33, 37, 41, 0.9); padding: 10px; border-radius: 4px;">
                                        <canvas id="geoDistributionChart"></canvas>
                                    </div>
                                </div>

                                @php
                                $sites = [
                                    'Multi-Regional' => ['icon' => 'globe', 'color' => 'primary'],
                                    'Nationwide' => ['icon' => 'flag', 'color' => 'success'],
                                    'Region-specific' => ['icon' => 'map', 'color' => 'warning'],
                                    'DOH - Central Office' => ['icon' => 'building', 'color' => 'danger']
                                ];
                                @endphp

                                <div class="row g-4 mt-auto">
                                    @foreach($sites as $site => $config)
                                    @php
                                        $count = $filteredProjects->where('sites', $site)->count();
                                        $percentage = $totalProjects > 0 ? ($count / $totalProjects) * 100 : 0;
                                    @endphp
                                    <div class="col-6 {{ !$loop->first && $loop->iteration > 2 ? 'mt-2' : '' }}">
                                        <div class="p-2 rounded bg-light">
                                            <div class="d-flex align-items-center">
                                                <i class="fa fa-{{ $config['icon'] }} text-{{ $config['color'] }} me-2" style="font-size: 1.2rem;"></i>
                                                <small class="text-muted">
                                                    <strong style="font-size: 1.2rem; cursor:pointer;" class="geo-count-link" data-type="{{ $site }}">{{ $count }}</strong>
                                                    <span class="badge bg-{{ $config['color'] }} ms-1">
                                                        {{ number_format($percentage, 1) }}%
                                                    </span>
                                                    <br> {{ $site }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const ctx = document.getElementById('geoDistributionChart').getContext('2d');
                                    new Chart(ctx, {
                                        type: 'doughnut',
                                        data: {
                                            labels: ['Multi-Regional', 'Nationwide', 'Region-specific', 'DOH-CO'],
                                            datasets: [{
                                                data: [
                                                    {{ $filteredProjects->where('sites', 'Multi-Regional')->count() }},
                                                    {{ $filteredProjects->where('sites', 'Nationwide')->count() }},
                                                    {{ $filteredProjects->where('sites', 'Region-specific')->count() }},
                                                    {{ $filteredProjects->where('sites', 'DOH - Central Office')->count() }}
                                                ],
                                                backgroundColor: [
                                                    'rgba(13, 110, 253, 0.9)',
                                                    'rgba(25, 135, 84, 0.9)',
                                                    'rgba(255, 193, 7, 0.9)',
                                                    'rgba(220, 53, 69, 0.9)'
                                                ],
                                                borderColor: 'rgba(255, 255, 255, 0.8)',
                                                borderWidth: 1
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            maintainAspectRatio: false,
                                            plugins: {
                                                legend: {
                                                    display: false
                                                },
                                                tooltip: {
                                                    enabled: true,
                                                    callbacks: {
                                                        label: function(context) {
                                                            const value = context.raw;
                                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                            const percentage = ((value / total) * 100).toFixed(1);
                                                            return `${value} (${percentage}%)`;
                                                        }
                                                    }
                                                }
                                            },
                                            cutout: '60%'
                                        }
                                    });
                                });
                                </script>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="region">
                        <div class="card border-0 shadow-sm w-100" style="background: linear-gradient(135deg, #757575, #424242);">
                            <div class="card-body p-4 d-flex flex-column">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="rounded-circle p-2 me-3" style="background: rgba(255,255,255,0.2)">
                                        <i class="fas fa-map-marker-alt text-white"></i>
                                    </div>
                                    <h5 class="card-title fw-bold text-white mb-0">Projects per Region</h5>
                                </div>
                                <div class="region-stats flex-grow-1" style="height: 180px; overflow-y: auto; margin-top: 22px; padding-right: 15px;">
                                    @foreach(\App\Models\ref_region::orderBy('nscb_reg_name')->get() as $region)
                                        @php
                                            $filterYear = request('year'); // Get selected year from URL
                                            $count = \App\Models\Project::getProjectCountByRegion($region, $filterYear);
                                        @endphp
                                        @if($count > 0)
                                            <div class="mb-2 p-2 rounded-3" style="background: rgba(255,255,255,0.1);">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="text-white">{{ $region->nscb_reg_name }}</span>
                                                    <span class="badge bg-white text-success region-count-link" style="cursor:pointer;"
                                                        data-region="{{ $region->nscb_reg_name }}">
                                                        {{ $count }}
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex" id="healthAreaSummaryCard">
                        <div class="card border-0 shadow-lg w-100" style="background: linear-gradient(135deg, #1a237e, #283593);">
                            <div class="card-body d-flex flex-column position-relative overflow-hidden">
                                <div class="d-flex align-items-center mb-3 mt-2">
                                    <h5 class="card-title fw-bold text-white mb-4">
                                        <span style="cursor: pointer;" onclick="toggleAllHealthAreaCards()">Health Area</span>
                                        <i class="fa fa-chevron-down ms-auto" id="healthAreaToggleIcon" style="transition: transform 0.3s ease; cursor: pointer;" onclick="toggleAllHealthAreaCards(); this.classList.toggle('rotated');"></i>
                                    </h5>
                                </div>
                                <div class="w-100 mt-3" style="max-height: 180px; overflow-y: auto; cursor: pointer;">
                                    <div class="mb-2 p-2 rounded-3 d-flex justify-content-center align-items-center health-area-item" style="background: #112240; transition: background 0.2s;" onclick="toggleHealthAreaCard('healthAreaLevel1Card')">
                                        <span class="fw-semibold text-white">Health Area (Level 1)</span>
                                    </div>
                                    <div class="mb-2 p-2 rounded-3 d-flex justify-content-center align-items-center health-area-item" style="background: #112240; transition: background 0.2s;" onclick="toggleHealthAreaCard('healthAreaLevel2Card')">
                                        <span class="fw-semibold text-white">Health Area (Level 2)</span>
                                    </div>
                                    <div class="mb-2 p-2 rounded-3 d-flex justify-content-center align-items-center health-area-item" style="background: #112240; transition: background 0.2s;" onclick="toggleHealthAreaCard('healthAreaLevel3Card')">
                                        <span class="fw-semibold text-white">Health Systems Building Blocks</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="healthAreaLevel1Card">
                        <div class="card border-0 shadow-lg w-100" style="background: linear-gradient(135deg,hsl(161, 97.10%, 13.50%), #185a9d);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
                                <div class="d-flex align-items-center mb-3 mt-2">
                                    <h5 class="card-title fw-bold text-white mb-0">Health Area (Level 1)</h5>
                                </div>
                                <div class="w-100 mt-3" style="max-height: 180px; overflow-y: auto;">
                                    @php
                                        // Get all Level 1 health areas, including N/A if present in data
                                        $level1s = \App\Models\ref_level1::orderBy('level1_desc')->get();
                                        $filterYear = request('year');
                                        $projectIds = \App\Models\Project::when($filterYear, function($q) use ($filterYear) {
                                            $q->where('year', $filterYear);
                                        })->pluck('project_id');
                                        // Get counts of unique project_ids per level1 from levels table, including N/A
                                        $level1Counts = \App\Models\Level::whereIn('project_id', $projectIds)
                                            ->whereNotNull('level1')
                                            ->select('level1', \DB::raw('COUNT(DISTINCT project_id) as count'))
                                            ->groupBy('level1')
                                            ->pluck('count', 'level1');
                                        // If there are projects with level1 == 'N/A' but not in ref_level1, add a pseudo entry
                                        $hasNA = isset($level1Counts['N/A']);
                                    @endphp
                                    @foreach($level1s as $level1)
                                        <div class="mb-2 p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                                            <span class="text-white">{{ $level1->level1_desc }}</span>
                                            <span class="badge bg-white text-success healtharea-level1-count-link" style="cursor:pointer;"
                                                data-level1="{{ $level1->level1_desc }}">
                                                {{ $level1Counts[$level1->level1_desc] ?? 0 }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($hasNA)
                                        <div class="mb-2 p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                                            <span class="text-white">N/A</span>
                                            <span class="badge bg-white text-success healtharea-level1-count-link" style="cursor:pointer;"
                                                data-level1="N/A">
                                                {{ $level1Counts['N/A'] ?? 0 }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="healthAreaLevel2Card">
                        <div class="card border-0 shadow-lg w-100" style="background: linear-gradient(135deg, #8E44AD, #9B59B6);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
                                <div class="d-flex align-items-center mb-3 mt-2">
                                    <h5 class="card-title fw-bold text-white mb-0">Health Area (Level 2)</h5>
                                </div>
                                <div class="w-100 mt-3" style="max-height: 180px; overflow-y: auto;">
                                    @php
                                        $level2s = \App\Models\ref_level2::orderBy('level2_desc')->get();
                                        $level2Counts = \App\Models\Level::whereIn('project_id', $projectIds)
                                            ->whereNotNull('level2')
                                            ->select('level2', \DB::raw('COUNT(DISTINCT project_id) as count'))
                                            ->groupBy('level2')
                                            ->pluck('count', 'level2');
                                        $hasNA2 = isset($level2Counts['N/A']);
                                    @endphp
                                    @foreach($level2s as $level2)
                                        <div class="mb-2 p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                                            <span class="text-white">{{ $level2->level2_desc }}</span>
                                            <span class="badge bg-white text-success healtharea-level2-count-link" style="cursor:pointer;"
                                                data-level2="{{ $level2->level2_desc }}">
                                                {{ $level2Counts[$level2->level2_desc] ?? 0 }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($hasNA2)
                                        <div class="mb-2 p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                                            <span class="text-white">N/A</span>
                                            <span class="badge bg-white text-success healtharea-level2-count-link" style="cursor:pointer;"
                                                data-level2="N/A">
                                                {{ $level2Counts['N/A'] ?? 0 }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="healthAreaLevel3Card">
                        <div class="card border-0 shadow-lg w-100" style="background: linear-gradient(135deg, #2980b9, #6dd5fa);">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 250px;">
                                <div class="d-flex align-items-center mb-3 mt-2">
                                    <h5 class="card-title fw-bold text-white mb-0">Health Systems Building Blocks</h5>
                                </div>
                                <div class="w-100 mt-3" style="max-height: 180px; overflow-y: auto;">
                                    @php
                                        $level3s = \App\Models\ref_level3::orderBy('level3_desc')->get();
                                        $level3Counts = \App\Models\Level::whereIn('project_id', $projectIds)
                                            ->whereNotNull('level3')
                                            ->select('level3', \DB::raw('COUNT(DISTINCT project_id) as count'))
                                            ->groupBy('level3')
                                            ->pluck('count', 'level3');
                                        $hasNA3 = isset($level3Counts['N/A']);
                                    @endphp
                                    @foreach($level3s as $level3)
                                        <div class="mb-2 p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                                            <span class="text-white">{{ $level3->level3_desc }}</span>
                                            <span class="badge bg-white text-success healtharea-level3-count-link" style="cursor:pointer;"
                                                data-level3="{{ $level3->level3_desc }}">
                                                {{ $level3Counts[$level3->level3_desc] ?? 0 }}
                                            </span>
                                        </div>
                                    @endforeach
                                    @if($hasNA3)
                                        <div class="mb-2 p-2 rounded-3 d-flex justify-content-between align-items-center" style="background: rgba(255,255,255,0.1);">
                                            <span class="text-white">N/A</span>
                                            <span class="badge bg-white text-success healtharea-level3-count-link" style="cursor:pointer;"
                                                data-level3="N/A">
                                                {{ $level3Counts['N/A'] ?? 0 }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex" id="financialManagementCard">
                        <div class="card border-0 shadow-lg w-100">
                            <!-- Header with gradient background -->
                            <div class="card-header" style="background: linear-gradient(135deg, rgb(80, 108, 163) 0%, #233554 100%); min-height: 70px; height: 70px; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                <div class="d-flex align-items-center" style="height: 100%;">
                                    <h5 class="card-title fw-bold mb-0 text-white" style="font-size: 1.15rem; margin-bottom: 0;">
                                        <span style="cursor: pointer;" onclick="toggleFinancialCards()">Financial Management</span>
                                    </h5>
                                    <i class="fa fa-chevron-down ms-2 text-white" id="financialToggleIcon" style="transition: transform 0.3s ease; cursor: pointer;" onclick="toggleFinancialCards(); this.classList.toggle('rotated');"></i>
                                </div>
                                <style>
                                    #financialToggleIcon.rotated {
                                        transform: rotate(180deg);
                                    }
                                </style>
                            </div>
                            <!-- Body with white background -->
                            <div class="card-body d-flex flex-column justify-content-center align-items-center" style="min-height: 180px; background: #fff; border-bottom-left-radius: .5rem; border-bottom-right-radius: .5rem;">
                                <div class="financial-stats flex-grow-1" style="height: 180px; overflow-y: auto;">
                                    <div class="mb-3 p-2 rounded-3" style="background: rgba(255,255,255,0.1);">
                                        <small class="fw-semibold mb-2 d-block" style="letter-spacing: 1px; color: black;">
                                            Total Budget for {{ $totalProjects }} Projects
                                        </small>
                                        <h3 class="card-text display-6 fw-bold mb-0"
                                            style="font-size: 1.5rem; color: #233554;">
                                            ₱ {{ number_format(\App\Models\FinancialAccomplishment::sumLatestBudget($filterYear), 2, '.', ',') }}
                                        </h3>
                                    </div>
                                    <div class="mt-4 p-2 rounded-3" style="background: rgba(255,255,255,0.1);">
                                        <small class="fw-semibold mb-2 d-block" style="letter-spacing: 1px; color: black;">
                                            Total Disbursement for {{ $totalProjects }} Projects
                                        </small>
                                        <h3 class="card-text display-6 fw-bold mb-0"
                                            style="font-size: 1.5rem; color: #233554;">
                                            ₱ {{ number_format(\App\Models\FinancialAccomplishment::sumLatestDisbursements($filterYear), 2, '.', ',') }}
                                        </h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="totalBudgetCard">
                        <div class="card border-0 shadow-lg w-100 d-flex flex-column" style="height: 100%;">
                            <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(135deg, #ff9800 0%, #ffb74d 100%); min-height: 70px; height: 70px; margin-bottom: 0;">
                                <i class="fas fa-piggy-bank me-2 text-black"></i> <span class="text-black">Total Budget by Funding Source</span>
                            </div>
                            <div class="card-body p-3 d-flex flex-column flex-grow-1" style="background: #fff; border-bottom-left-radius: .5rem; border-bottom-right-radius: .5rem; min-height: 0;">
                                @php
                                    // Get all ref_funds (so all funds_code are present)
                                    $allFunds = \App\Models\ref_funds::orderBy('funds_code')->get();
                                    // Get all unique funding_source (which is funds_desc) from projects
                                    $fundingSources = \App\Models\Project::select('funding_source')->distinct()->pluck('funding_source')->toArray();
                                    // Get budget by funds_desc (as key)
                                    $budgetByFundingSource = \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSource($filterYear);
                                    // Use a single color for all funding sources
                                    $defaultColor = '#0d1a4a';
                                    // Calculate total budget (only for those with budget)
                                    $totalBudget = array_sum($budgetByFundingSource);

                                    // Build funding source list from ref_funds, so all funds_code are present
                                    $fundingSourceList = [];
                                    foreach ($allFunds as $fund) {
                                        $fundingSourceList[] = [
                                            'funds_desc' => $fund->funds_desc,
                                            'funds_code' => $fund->funds_code,
                                        ];
                                    }
                                @endphp
                                <div>
                                    <h2 class="fw-bold text-dark mb-0">₱{{ number_format($totalBudget, 2) }}</h2>
                                    <small class="text-muted">Total Budget</small>
                                </div>
                                <div class="flex-grow-1 overflow-auto" style="min-height: 0; max-height: 150px;">
                                    @foreach($fundingSourceList as $idx => $item)
                                        @php
                                            $funds_desc = $item['funds_desc'];
                                            $funds_code = $item['funds_code'];
                                            $budget = isset($budgetByFundingSource[$funds_desc]) ? $budgetByFundingSource[$funds_desc] : 0;
                                            $percentage = $totalBudget > 0 ? ($budget / $totalBudget) * 100 : 0;
                                            $color = $defaultColor;
                                        @endphp
                                        <div class="d-flex align-items-center {{ $idx !== count($fundingSourceList) - 1 ? 'mb-2' : '' }}">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <i class="fa fa-circle me-1" style="color: {{ $color }}"></i>
                                                <small class="text-dark">{{ $funds_code }}</small>
                                            </div>
                                            <div class="text-end" style="min-width: 120px;">
                                                <span class="fw-bold text-secondary">₱{{ number_format($budget, 2) }}</span>
                                                <span class="badge ms-1" style="background-color: {{ $color }}; color: #fff;">{{ number_format($percentage, 2) }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress {{ $idx !== count($fundingSourceList) - 1 ? 'mb-2' : '' }}" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $color }}" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 d-flex d-none" id="totalDisbursementCard">
                        <div class="card border-0 shadow-lg w-100 d-flex flex-column" style="height: 100%;">
                            <div class="card-header text-white fw-bold d-flex align-items-center" style="background: linear-gradient(135deg, #1976d2 0%, #64b5f6 100%); min-height: 70px; height: 70px; margin-bottom: 0;">
                                <i class="fas fa-coins me-2 text-black"></i> <span class="text-black">Total Disbursement by Funding Source</span>
                            </div>
                            <div class="card-body p-3 d-flex flex-column flex-grow-1" style="background: #fff; border-bottom-left-radius: .5rem; border-bottom-right-radius: .5rem; min-height: 0;">
                                @php
                                    // Get all ref_funds (so all funds_code are present)
                                    $allFunds = \App\Models\ref_funds::orderBy('funds_code')->get();
                                    // Get all unique funding_source (which is funds_desc) from projects
                                    $fundingSources = \App\Models\Project::select('funding_source')->distinct()->pluck('funding_source')->toArray();
                                    // Get disbursement by funds_desc (as key)
                                    $disbursementByFundingSource = \App\Models\FinancialAccomplishment::sumLatestDisbursementPerFundingSource($filterYear);
                                    // Use a single color for all funding sources
                                    $defaultColor = '#1976d2';
                                    // Calculate total disbursement (only for those with disbursement)
                                    $totalDisbursement = array_sum($disbursementByFundingSource);

                                    // Build funding source list from ref_funds, so all funds_code are present
                                    $fundingSourceList = [];
                                    foreach ($allFunds as $fund) {
                                        $fundingSourceList[] = [
                                            'funds_desc' => $fund->funds_desc,
                                            'funds_code' => $fund->funds_code,
                                        ];
                                    }
                                @endphp
                                <div>
                                    <h2 class="fw-bold text-dark mb-0">₱{{ number_format($totalDisbursement, 2) }}</h2>
                                    <small class="text-muted">Total Disbursement</small>
                                </div>
                                <div class="flex-grow-1 overflow-auto" style="min-height: 0; max-height: 150px;">
                                    @foreach($fundingSourceList as $idx => $item)
                                        @php
                                            $funds_desc = $item['funds_desc'];
                                            $funds_code = $item['funds_code'];
                                            $disbursement = isset($disbursementByFundingSource[$funds_desc]) ? $disbursementByFundingSource[$funds_desc] : 0;
                                            $percentage = $totalDisbursement > 0 ? ($disbursement / $totalDisbursement) * 100 : 0;
                                            $color = $defaultColor;
                                        @endphp
                                        <div class="d-flex align-items-center {{ $idx !== count($fundingSourceList) - 1 ? 'mb-2' : '' }}">
                                            <div class="d-flex align-items-center flex-grow-1">
                                                <i class="fa fa-circle me-1" style="color: {{ $color }}"></i>
                                                <small class="text-dark">{{ $funds_code }}</small>
                                            </div>
                                            <div class="text-end" style="min-width: 120px;">
                                                <span class="fw-bold text-secondary">₱{{ number_format($disbursement, 2) }}</span>
                                                <span class="badge ms-1" style="background-color: {{ $color }}; color: #fff;">{{ number_format($percentage, 2) }}%</span>
                                            </div>
                                        </div>
                                        <div class="progress {{ $idx !== count($fundingSourceList) - 1 ? 'mb-2' : '' }}" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%; background-color: {{ $color }}" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-3 d-flex">
                        <div class="card shadow-lg border-0 w-100" style="background: linear-gradient(135deg, #FF4B2B, #FF416C);">
                            <div class="card-body d-flex flex-column">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="me-2">
                                        <i class="fa fa-tasks fa-fw text-white"></i>
                                    </div>
                                    <h5 class="card-title fw-bold text-white mb-0">Physical Accomplishments</h5>
                                </div>

                                @php
                                    $physicalStats = \App\Models\PhysicalAccomplishment::getLatestStatsByYear($filterYear);
                                @endphp

                                <div class="accomplishment-stats flex-grow-1" style="height: 180px; overflow-y: auto; padding-right: 15px;">

                                     INFRASTRUCTURE PROJECTS
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2" style="margin-top: 35px;">
                                            <small class="fw-semibold text-white">Infrastructure Projects</small>
                                            <span class="badge bg-white text-dark project-count-link"
                                                style="font-size: 1.1em; margin-right: 10px; cursor:pointer;"
                                                data-type="infra" data-status="all">
                                                {{ $physicalStats['total']['infra'] }}
                                            </span>
                                        </div>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-white">Behind Schedule</span>
                                                <span class="badge project-count-link" style="background: rgba(255,193,7,0.9); cursor:pointer;"
                                                    data-type="infra" data-status="behind">
                                                    {{ $physicalStats['infra']['behind'] }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-white">On Time</span>
                                                <span class="badge project-count-link" style="background: rgba(40,167,69,0.9); cursor:pointer;"
                                                    data-type="infra" data-status="on_time">
                                                    {{ $physicalStats['infra']['on_time'] }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-white">Ahead</span>
                                                <span class="badge project-count-link" style="background: rgba(23,162,184,0.9); cursor:pointer;"
                                                    data-type="infra" data-status="ahead">
                                                    {{ $physicalStats['infra']['ahead'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                     NON-INFRASTRUCTURE PROJECTS
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="fw-semibold text-white">Non-Infrastructure Projects</small>
                                            <span class="badge bg-white text-dark project-count-link"
                                                style="font-size: 1.1em; margin-right: 10px; cursor:pointer;"
                                                data-type="non_infra" data-status="all">
                                                {{ $physicalStats['total']['non_infra'] }}
                                            </span>
                                        </div>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-white">Behind Schedule</span>
                                                <span class="badge project-count-link" style="background: rgba(255,193,7,0.9); cursor:pointer;"
                                                    data-type="non_infra" data-status="behind">
                                                    {{ $physicalStats['non_infra']['behind'] }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-white">On Time</span>
                                                <span class="badge project-count-link" style="background: rgba(40,167,69,0.9); cursor:pointer;"
                                                    data-type="non_infra" data-status="on_time">
                                                    {{ $physicalStats['non_infra']['on_time'] }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-white">Ahead</span>
                                                <span class="badge project-count-link" style="background: rgba(23,162,184,0.9); cursor:pointer;"
                                                    data-type="non_infra" data-status="ahead">
                                                    {{ $physicalStats['non_infra']['ahead'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                     COMBINED PROJECTS
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <small class="fw-semibold text-white">Combined Projects</small>
                                            <span class="badge bg-white text-dark project-count-link"
                                                style="font-size: 1.1em; margin-right: 10px; cursor:pointer;"
                                                data-type="combined" data-status="all">
                                                {{ $physicalStats['total']['combined'] }}
                                            </span>
                                        </div>
                                        <div class="bg-white bg-opacity-10 rounded-3 p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-white">Behind Schedule</span>
                                                <span class="badge project-count-link" style="background: rgba(255,193,7,0.9); cursor:pointer;"
                                                    data-type="combined" data-status="behind">
                                                    {{ $physicalStats['combined']['behind'] }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="text-white">On Time</span>
                                                <span class="badge project-count-link" style="background: rgba(40,167,69,0.9); cursor:pointer;"
                                                    data-type="combined" data-status="on_time">
                                                    {{ $physicalStats['combined']['on_time'] }}
                                                </span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="text-white">Ahead</span>
                                                <span class="badge project-count-link" style="background: rgba(23,162,184,0.9); cursor:pointer;"
                                                    data-type="combined" data-status="ahead">
                                                    {{ $physicalStats['combined']['ahead'] }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> -->

                    <!-- Modal for showing project list -->
                    <div class="modal fade" id="projectListModal" tabindex="-1" aria-labelledby="projectListModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="projectListModalLabel">Project List</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body" id="projectListModalBody">
                                    <!-- Project list will be dynamically inserted here via JS -->
                                    <div class="text-center text-muted">Click on a count to view projects</div>
                                </div>
                            </div>
                        </div>
                    </div>
                                        <script>
                        // Toggle function for dashboard cards
                        function toggleDashboardCards() {
                            const cards = [
                                'fundingSources',
                                'fundTypesCard',
                                'depdevCard',
                                'managementCard',
                                'geoDistributionCard',
                                'region'
                            ];

                            const toggleIcon = document.getElementById('toggleIcon');
                            const isVisible = !document.getElementById('fundingSources').classList.contains('d-none');

                            cards.forEach(cardId => {
                                const card = document.getElementById(cardId);
                                if (card) {
                                    if (isVisible) {
                                        card.classList.add('d-none');
                                    } else {
                                        card.classList.remove('d-none');
                                    }
                                }
                            });

                            // Rotate the toggle icon
                            if (isVisible) {
                                toggleIcon.style.transform = 'rotate(0deg)';
                            } else {
                                toggleIcon.style.transform = 'rotate(180deg)';
                            }
                        }

                                                                        // Add hover effect for the DOH Portfolio text and icon
                        document.addEventListener('DOMContentLoaded', function() {
                            const dohText = document.querySelector('#dohPortfolioCard span');
                            const dohIcon = document.getElementById('toggleIcon');

                            if (dohText) {
                                dohText.addEventListener('mouseenter', function() {
                                    this.style.textDecoration = 'underline';
                                    this.style.opacity = '0.8';
                                });

                                dohText.addEventListener('mouseleave', function() {
                                    this.style.textDecoration = 'none';
                                    this.style.opacity = '1';
                                });
                            }

                            if (dohIcon) {
                                dohIcon.addEventListener('mouseenter', function() {
                                    this.style.transform = 'scale(1.1)';
                                });

                                dohIcon.addEventListener('mouseleave', function() {
                                    this.style.transform = 'scale(1)';
                                });
                            }
                        });

                        // Toggle function for financial cards
                        function toggleFinancialCards() {
                            const financialCards = [
                                'totalBudgetCard',
                                'totalDisbursementCard'
                            ];

                            const toggleIcon = document.getElementById('financialToggleIcon');
                            const isVisible = !document.getElementById('totalBudgetCard').classList.contains('d-none');

                            financialCards.forEach(cardId => {
                                const card = document.getElementById(cardId);
                                if (card) {
                                    if (isVisible) {
                                        card.classList.add('d-none');
                                    } else {
                                        card.classList.remove('d-none');
                                    }
                                }
                            });

                            // Rotate the toggle icon
                            if (isVisible) {
                                toggleIcon.style.transform = 'rotate(0deg)';
                            } else {
                                toggleIcon.style.transform = 'rotate(180deg)';
                            }
                        }

                        // Function to toggle individual health area card
                        function toggleHealthAreaCard(cardId) {
                            const selectedCard = document.getElementById(cardId);
                            if (selectedCard) {
                                // If the card is currently visible, hide it
                                if (!selectedCard.classList.contains('d-none')) {
                                    selectedCard.classList.add('d-none');
                                } else {
                                    // If the card is hidden, hide all other cards first, then show this one
                                    const healthAreaCards = [
                                        'healthAreaLevel1Card',
                                        'healthAreaLevel2Card',
                                        'healthAreaLevel3Card'
                                    ];

                                    healthAreaCards.forEach(id => {
                                        const card = document.getElementById(id);
                                        if (card) {
                                            card.classList.add('d-none');
                                        }
                                    });

                                    // Show the selected card
                                    selectedCard.classList.remove('d-none');
                                }
                            }
                        }

                        // Function to hide all health area cards
                        function hideAllHealthAreaCards() {
                            const healthAreaCards = [
                                'healthAreaLevel1Card',
                                'healthAreaLevel2Card',
                                'healthAreaLevel3Card'
                            ];

                            healthAreaCards.forEach(id => {
                                const card = document.getElementById(id);
                                if (card) {
                                    card.classList.add('d-none');
                                }
                            });
                        }

                                                // Add hover effect for the Financial Management text and icon
                        document.addEventListener('DOMContentLoaded', function() {
                            const financialText = document.querySelector('#financialManagementCard span');
                            const financialIcon = document.getElementById('financialToggleIcon');

                            if (financialText) {
                                financialText.addEventListener('mouseenter', function() {
                                    this.style.textDecoration = 'underline';
                                    this.style.opacity = '0.8';
                                });

                                financialText.addEventListener('mouseleave', function() {
                                    this.style.textDecoration = 'none';
                                    this.style.opacity = '1';
                                });
                            }

                            if (financialIcon) {
                                financialIcon.addEventListener('mouseenter', function() {
                                    this.style.transform = 'scale(1.1)';
                                });

                                financialIcon.addEventListener('mouseleave', function() {
                                    this.style.transform = 'scale(1)';
                                });
                            }


                        });

                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('.project-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const type = el.getAttribute('data-type');
                                    const status = el.getAttribute('data-status');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';

                                    // Set modal title based on type and status
                                    const typeLabels = {
                                        'infra': 'Infrastructure',
                                        'non_infra': 'Non-Infrastructure',
                                        'combined': 'Combined'
                                    };
                                    const statusLabels = {
                                        'behind': 'Behind Schedule',
                                        'on_time': 'On Time',
                                        'ahead': 'Ahead of Schedule',
                                        'all': ''
                                    };

                                    let modalTitle = '';
                                    if (status === 'all') {
                                        modalTitle = `${typeLabels[type] || type} Projects`;
                                    } else {
                                        modalTitle = `${typeLabels[type] || type} Projects - ${statusLabels[status] || status}`;
                                    }
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;

                                    // Show loading
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();

                                    fetch("{{ route('dashboard.physical-project-list') }}?type=" + type + "&status=" + status + "&year=" + yearParam)
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('Network response was not ok');
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            console.log('AJAX response:', data); // Debug log
                                            // If backend returns {data: [...]}, use data.data
                                            if (data && Array.isArray(data.data)) {
                                                data = data.data;
                                            }
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });

                                    // Add event listener to refresh page when modal is closed
                                    // Only add once
                                    if (!modalElement.dataset.refreshListenerAdded) {
                                        modalElement.addEventListener('hidden.bs.modal', function() {
                                            location.reload();
                                        });
                                        modalElement.dataset.refreshListenerAdded = "true";
                                    }
                                });
                            });

                            // Funding source modal functionality
                            document.querySelectorAll('.funding-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const fund = el.getAttribute('data-fund');
                                    const status = el.getAttribute('data-status');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';

                                    // Set modal title
                                    const modalTitle = status === 'all' ? `Projects - ${fund}` : `Projects - ${fund} (${status})`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;

                                    // Show loading
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();

                                    fetch("{{ route('dashboard.funding-project-list') }}?fund=" + encodeURIComponent(fund) + "&status=" + status + "&year=" + yearParam)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Fund type modal functionality
                            document.querySelectorAll('.fund-type-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const fundType = el.getAttribute('data-type');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';

                                    // Set modal title
                                    const modalTitle = `${fundType} Projects`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;

                                    // Show loading
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();

                                    fetch("{{ route('dashboard.fund-type-project-list') }}?fund_type=" + encodeURIComponent(fundType) + "&year=" + yearParam)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Geographical distribution modal functionality
                            document.querySelectorAll('.geo-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const site = el.getAttribute('data-type');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';

                                    // Set modal title
                                    const modalTitle = `Projects - ${site}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;

                                    // Show loading
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();

                                    fetch("{{ route('dashboard.geo-project-list') }}?site=" + encodeURIComponent(site) + "&year=" + yearParam)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Region projects modal functionality
                            document.querySelectorAll('.region-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const region = el.getAttribute('data-region');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';

                                    // Set modal title
                                    const modalTitle = `Projects - ${region}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;

                                    // Show loading
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();

                                    fetch("{{ route('dashboard.region-project-list') }}?region=" + encodeURIComponent(region) + "&year=" + yearParam)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // DePDev classification modal functionality
                            document.querySelectorAll('.depdev-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const depdev = el.getAttribute('data-depdev');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';
                                    const modalTitle = `Projects - DePDev: ${depdev}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                    fetch(`/dashboard/depdev-project-list?depdev=${encodeURIComponent(depdev)}&year=${yearParam}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Management type modal functionality
                            document.querySelectorAll('.management-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const management = el.getAttribute('data-management');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';
                                    const modalTitle = `Projects - Management: ${management}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                    fetch(`/dashboard/management-project-list?management=${encodeURIComponent(management)}&year=${yearParam}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Health Area Level 1 modal functionality
                            document.querySelectorAll('.healtharea-level1-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const level1 = el.getAttribute('data-level1');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';
                                    const modalTitle = `Projects - Health Area Level 1: ${level1}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                    fetch(`/dashboard/healtharea-level1-project-list?level1=${encodeURIComponent(level1)}&year=${yearParam}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Health Area Level 2 modal functionality
                            document.querySelectorAll('.healtharea-level2-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const level2 = el.getAttribute('data-level2');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';
                                    const modalTitle = `Projects - Health Area Level 2: ${level2}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                    fetch(`/dashboard/healtharea-level2-project-list?level2=${encodeURIComponent(level2)}&year=${yearParam}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Health Area Level 3 modal functionality
                            document.querySelectorAll('.healtharea-level3-count-link').forEach(function(el) {
                                el.addEventListener('click', function() {
                                    const level3 = el.getAttribute('data-level3');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';
                                    const modalTitle = `Projects - Health Area Level 3: ${level3}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();
                                    fetch(`/dashboard/healtharea-level3-project-list?level3=${encodeURIComponent(level3)}&year=${yearParam}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });

                            // Portfolio modal functionality
                            document.querySelectorAll('.portfolio-count-link').forEach(function(el) {
                                el.addEventListener('click', function(event) {
                                    event.stopPropagation(); // Prevent triggering the card toggle
                                    const status = el.getAttribute('data-status');
                                    const year = @json($filterYear);
                                    const yearParam = year && year !== '' ? year : 'all';

                                    // Set modal title
                                    const modalTitle = `Projects - ${status}`;
                                    document.getElementById('projectListModalLabel').textContent = modalTitle;

                                    // Show loading
                                    document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-muted">Loading...</div>';
                                    let modalElement = document.getElementById('projectListModal');
                                    let modal = new bootstrap.Modal(modalElement);
                                    modal.show();

                                    fetch("{{ route('dashboard.portfolio-project-list') }}?status=" + encodeURIComponent(status) + "&year=" + yearParam)
                                        .then(response => response.json())
                                        .then(data => {
                                            let html = '';
                                            if (!Array.isArray(data) || data.length === 0) {
                                                html = '<div class="text-center text-muted">No projects found for the selected criteria.</div>';
                                            } else {
                                                html = '<div class="table-responsive"><table class="table table-bordered table-striped mb-0"><thead><tr><th>No.</th><th>Project ID</th><th>Project Title</th><th>Project Name</th><th>Action</th></tr></thead><tbody>';
                                                data.forEach(function(project, idx) {
                                                    html += '<tr><td>' + (idx + 1) + '</td><td>' + (project.project_id || 'N/A') + '</td><td>' + (project.short_title || 'N/A') + '</td><td>' + (project.project_name || 'N/A') + '</td>';
                                                    html += '<td class="text-center">';
                                                    if (project.id) {
                                                        html += '<a href="/projects/' + project.id + '" title="View Project"><img src="/images/view.png" width="18" height="18" alt="View"></a>';
                                                    } else {
                                                        html += '<span class="text-muted">N/A</span>';
                                                    }
                                                    html += '</td></tr>';
                                                });
                                                html += '</tbody></table></div>';
                                            }
                                            document.getElementById('projectListModalBody').innerHTML = html;
                                        })
                                        .catch((error) => {
                                            console.error('Error:', error);
                                            document.getElementById('projectListModalBody').innerHTML = '<div class="text-center text-danger">Failed to load projects. Please try again.</div>';
                                        });
                                });
                            });
                        });
                    </script>

                </div>

                <script>
                    function toggleProjectDetails() {
                        const details = document.getElementById('projectDetails');
                        const details1 = document.getElementById('projectDetails1');
                        const icon = document.querySelector('.fa-chevron-down, .fa-chevron-up');

                        if (details.classList.contains('d-none')) {
                            details.classList.remove('d-none');
                            details.classList.add('d-flex');
                            details1.classList.remove('d-none');
                            details1.classList.add('d-flex');
                            icon.classList.replace('fa-chevron-down', 'fa-chevron-up');
                        } else {
                            details.classList.remove('d-flex');
                            details.classList.add('d-none');
                            details1.classList.remove('d-flex');
                            details1.classList.add('d-none');
                            icon.classList.replace('fa-chevron-up', 'fa-chevron-down');
                        }
                    }
                </script>

                <div class="row mt-4">
                    <div class="col-md-9">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title">Project Trends</h5>
                                <div class="dropdown">
                                    <div class="d-flex align-items-center">
                                        <span class="me-2">
                                            <small class="text-muted">Filter by Status:</small>
                                        </span>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-outline-secondary active" onclick="updateChart('total')">All</button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="updateChart('pipeline')">Pipeline</button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="updateChart('active')">Active</button>
                                            <button class="btn btn-sm btn-outline-secondary" onclick="updateChart('completed')">Completed</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="projectTrendChart" ></canvas>
                            </div>

                            @php
                                $fundingSources = \App\Models\ref_funds::orderBy('funds_code')->pluck('funds_code');
                                $fundingSourceDescs = \App\Models\ref_funds::orderBy('funds_code')->pluck('funds_desc', 'funds_code');
                                $chartData = [
                                    'total' => [],
                                    'pipeline' => [],
                                    'active' => [],
                                    'completed' => [],
                                ];

                                // Build budget per status using FinancialAccomplishment helper
                                $budgetPerStatusAssoc = [
                                    'total' => \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSourceByStatus(null, $filterYear ?? null),
                                    'pipeline' => \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSourceByStatus('Pipeline', $filterYear ?? null),
                                    'active' => \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSourceByStatus('Active', $filterYear ?? null),
                                    'completed' => \App\Models\FinancialAccomplishment::sumLatestBudgetPerFundingSourceByStatus('Completed', $filterYear ?? null),
                                ];

                                $budgetData = [
                                    'total' => [],
                                    'pipeline' => [],
                                    'active' => [],
                                    'completed' => []
                                ];
                                foreach ($fundingSources as $code) {
                                    $desc = $fundingSourceDescs[$code] ?? '';
                                    $chartData['total'][] = [
                                        'count' => $filteredProjects->where('funding_source', $desc)->count()
                                    ];
                                    $chartData['pipeline'][] = [
                                        'count' => $filteredProjects->where('funding_source', $desc)->where('status', 'Pipeline')->count()
                                    ];
                                    $chartData['active'][] = [
                                        'count' => $filteredProjects->where('funding_source', $desc)->where('status', 'Active')->count()
                                    ];
                                    $chartData['completed'][] = [
                                        'count' => $filteredProjects->where('funding_source', $desc)->where('status', 'Completed')->count()
                                    ];
                                    // Budgets per status aligned with buttons
                                    $budgetData['total'][] = isset($budgetPerStatusAssoc['total'][$desc]) ? round((float) $budgetPerStatusAssoc['total'][$desc], 2) : 0.00;
                                    $budgetData['pipeline'][] = isset($budgetPerStatusAssoc['pipeline'][$desc]) ? round((float) $budgetPerStatusAssoc['pipeline'][$desc], 2) : 0.00;
                                    $budgetData['active'][] = isset($budgetPerStatusAssoc['active'][$desc]) ? round((float) $budgetPerStatusAssoc['active'][$desc], 2) : 0.00;
                                    $budgetData['completed'][] = isset($budgetPerStatusAssoc['completed'][$desc]) ? round((float) $budgetPerStatusAssoc['completed'][$desc], 2) : 0.00;
                                }
                            @endphp

                            <script>
                                let projectChart;
                                const fundingSources = {!! json_encode($fundingSources) !!};

                                const chartData = {
                                    total: {!! json_encode($chartData['total']) !!},
                                    pipeline: {!! json_encode($chartData['pipeline']) !!},
                                    active: {!! json_encode($chartData['active']) !!},
                                    completed: {!! json_encode($chartData['completed']) !!}
                                };
                                const budgetData = {!! json_encode($budgetData) !!};

                                // Track which datasets are hidden
                                let hiddenDatasets = {
                                    0: false, // Project Count
                                    1: false  // Budget
                                };

                                function formatCurrency2(value) {
                                    // Always show 2 decimal places
                                    return value.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                }

                                function createChart(data, label, budgetSeries) {
                                    if (projectChart) {
                                        // Save current hidden state before destroying
                                        hiddenDatasets[0] = projectChart.getDatasetMeta(0).hidden || false;
                                        hiddenDatasets[1] = projectChart.getDatasetMeta(1).hidden || false;
                                        projectChart.destroy();
                                    }

                                    const ctx = document.getElementById('projectTrendChart').getContext('2d');
                                    projectChart = new Chart(ctx, {
                                        type: 'line',
                                        data: {
                                            labels: fundingSources,
                                            datasets: [
                                                {
                                                    label: label + ' Count',
                                                    data: data.map(d => d.count),
                                                    borderColor: '#6B48FF',
                                                    backgroundColor: 'rgba(107, 72, 255, 0.1)',
                                                    borderWidth: 2,
                                                    fill: true,
                                                    tension: 0.4,
                                                    yAxisID: 'y',
                                                    hidden: hiddenDatasets[0]
                                                },
                                                {
                                                    label: 'Latest Total Budget',
                                                    data: budgetSeries,
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
                                                    hidden: hiddenDatasets[1],
                                                    datalabels: {
                                                        display: true,
                                                        color: '#FF9800',
                                                        anchor: 'end',
                                                        align: 'top',
                                                        offset: 10,
                                                        formatter: function(value, context) {
                                                            // Only show datalabels for budget
                                                            if (context.datasetIndex === 1) {
                                                                if (value && !isNaN(value)) {
                                                                    return '₱' + formatCurrency2(value);
                                                                }
                                                            }
                                                            return '';
                                                        },
                                                        font: {
                                                            weight: 'bold',
                                                            size: 11
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
                                                    display: !hiddenDatasets[0],
                                                    title: {
                                                        display: true,
                                                        text: 'Project Count'
                                                    },
                                                    ticks: {
                                                        stepSize: 1,
                                                        callback: function(value) {
                                                            if (Number.isInteger(value)) {
                                                                return value;
                                                            }
                                                        }
                                                    }
                                                },
                                                y1: {
                                                    beginAtZero: true,
                                                    position: 'right',
                                                    display: !hiddenDatasets[1],
                                                    grid: {
                                                        drawOnChartArea: false
                                                    },
                                                    title: {
                                                        display: true,
                                                        text: 'Latest Total Budget'
                                                    },
                                                    ticks: {
                                                        callback: function(value) {
                                                            // Format as currency with 2 decimal places
                                                            return '₱' + formatCurrency2(value);
                                                        }
                                                    }
                                                }
                                            },
                                            plugins: {
                                                legend: {
                                                    display: true,
                                                    position: 'bottom',
                                                    labels: {
                                                        padding: 20 // Add padding below legend labels
                                                    },
                                                    onClick: function(e, legendItem, legend) {
                                                        const index = legendItem.datasetIndex;
                                                        const chart = legend.chart;
                                                        const meta = chart.getDatasetMeta(index);

                                                        // Toggle dataset visibility
                                                        meta.hidden = meta.hidden === null ? !chart.data.datasets[index].hidden : null;

                                                        // Update Y-axis visibility based on dataset visibility
                                                        const dataset0Visible = !chart.getDatasetMeta(0).hidden;
                                                        const dataset1Visible = !chart.getDatasetMeta(1).hidden;

                                                        // Update the tracker
                                                        hiddenDatasets[0] = !dataset0Visible;
                                                        hiddenDatasets[1] = !dataset1Visible;

                                                        // Hide entire Y-axis (including scale) when dataset is hidden
                                                        chart.options.scales.y.display = dataset0Visible;
                                                        chart.options.scales.y1.display = dataset1Visible;

                                                        chart.update();
                                                    }
                                                },
                                                title: {
                                                    display: true,
                                                    text: 'Projects Overview',
                                                    position: 'top',
                                                    padding: {
                                                        top: 5,
                                                        bottom: 35
                                                    },
                                                    font: {
                                                        size: 14,
                                                        weight: 'bold'
                                                    }
                                                },
                                                datalabels: {
                                                    display: true,
                                                    color: '#6B48FF',
                                                    anchor: 'end',
                                                    align: 'bottom',
                                                    offset: 4,
                                                    formatter: function(value, context) {
                                                        // Show datalabels for project count (datasetIndex 0)
                                                        if (context.datasetIndex === 0) {
                                                            return value;
                                                        }
                                                        // For budget, handled by per-dataset datalabels above
                                                        return '';
                                                    },
                                                    font: {
                                                        weight: 'bold',
                                                        size: 11
                                                    }
                                                },
                                                tooltip: {
                                                    callbacks: {
                                                        label: function(context) {
                                                            if (context.datasetIndex === 1) {
                                                                // Budget
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

                                    document.getElementById('projectTrendChart').style.height = '400px';
                                }

                                function updateChart(type) {
                                    document.querySelectorAll('.btn-group .btn').forEach(btn => {
                                        btn.classList.remove('active');
                                    });
                                    event.target.classList.add('active');

                                    const labels = {
                                        total: 'Total Projects',
                                        pipeline: 'Pipeline Projects',
                                        active: 'Active Projects',
                                        completed: 'Completed Projects'
                                    };
                                    createChart(chartData[type], labels[type], budgetData[type]);
                                }

                                document.addEventListener('DOMContentLoaded', () => {
                                    createChart(chartData.total, 'Total Projects', budgetData.total);
                                });
                            </script>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">Recent Projects</h5>
                                <a href="{{ route('projects.index', ['year' => $filterYear]) }}" class="btn btn-sm btn-outline-secondary">View All</a>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    @php
                                        $recentProjects = \App\Models\Project::select('projects.id', 'projects.project_id', 'projects.short_title', 'projects.status')
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
                                    @forelse($recentProjects as $project)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <a href="{{ route('projects.show', $project->id) }}" class="d-flex justify-content-between align-items-center w-100 text-decoration-none text-dark">
                                            {{ $project->short_title }}
                                            <span></span>
                                            <span class="badge bg-{{ getStatusColor($project->status) }}">
                                                {{ ucfirst($project->status) }}
                                            </span>
                                        </a>
                                    </li>
                                    @empty
                                    <li class="list-group-item text-center text-muted py-3">
                                        No projects found
                                    </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const warning = "{{ session('warning') }}";
        if (warning) {
            Swal.fire({
                title: "Failed",
                text: warning,
                icon: "error"
            });
        }
        var message = "{{ session('success') }}";
        if (message) {
            Swal.fire({
                title: "Success",
                text: message,
                icon: "success"
            });
        }
    </script>

    <script>
    // ... existing code ...
    function toggleAllHealthAreaCards() {
        const healthAreaCards = [
            'healthAreaLevel1Card',
            'healthAreaLevel2Card',
            'healthAreaLevel3Card'
        ];
        // Check if any card is hidden
        const anyHidden = healthAreaCards.some(id => {
            const card = document.getElementById(id);
            return card && card.classList.contains('d-none');
        });
        healthAreaCards.forEach(id => {
            const card = document.getElementById(id);
            if (card) {
                if (anyHidden) {
                    card.classList.remove('d-none');
                } else {
                    card.classList.add('d-none');
                }
            }
        });
    }
    // ... existing code ...
    </script>

    <style>
        .health-area-item {
            cursor: pointer;
            transition: background 0.2s;
        }
        .health-area-item:hover {
            background: #1b2a4a !important;
        }
        .health-area-item span {
            transition: text-decoration 0.2s, opacity 0.2s;
        }
        .health-area-item:hover span {
            text-decoration: underline;
            opacity: 0.8;
        }
    </style>
@endif
</x-app-layout>
