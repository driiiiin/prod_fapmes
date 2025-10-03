<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <div class="container-fluid mt-4 pt-4" style="width: 95%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-left mb-0"><strong>Overall Area Distribution - Comprehensive Report</strong></h4>
                <a href="{{ route('overall_area_distribution') }}" class="btn btn-secondary btn-sm" style="float: right;">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>

            <div class="card-body">

                <!-- Executive Summary -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between" style="gap: 1rem;">
                                <h5 class="mb-0 flex-grow-1" style="font-size: 1.25rem;"><strong>Executive Summary</strong></h5>
                                <!-- Print Button -->
                                <div class="mb-0" style="flex-shrink: 0;">
                                    <button class="btn btn-primary btn-sm" style="font-size: 0.95rem; padding: 0.4rem 1rem;" onclick="printActiveTab()">
                                        <i class="bi bi-printer"></i> Print Report
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row justify-content-center">
                                    <div class="col-md-3 text-center">
                                        <div class="border rounded p-3">
                                            <h3 class="text-primary">{{ number_format(collect($fundingsources)->sum('project_count'), 0, '.', ',') }}</h3>
                                            <p class="mb-0"><strong>Total Projects</strong></p>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-center">
                                        <div class="border rounded p-3">
                                            <h3 class="text-success">₱{{ number_format(collect($fundingsources)->sum('total_budget'), 2, '.', ',') }}</h3>
                                            <p class="mb-0"><strong>Total Budget</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs" id="reportTab" role="tablist" style="font-size: 14px;">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="funding-sources-report-tab" data-bs-toggle="tab" href="#funding-sources-report" role="tab">Funding Source</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="management-types-report-tab" data-bs-toggle="tab" href="#management-types-report" role="tab">Management Type</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="depdev-report-tab" data-bs-toggle="tab" href="#depdev-report" role="tab">DePDev Classification</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="gph-report-tab" data-bs-toggle="tab" href="#gph-report" role="tab">Type of GPH Implemented</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="level1-report-tab" data-bs-toggle="tab" href="#level1-report" role="tab">Health Area (Level 1)</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="level2-report-tab" data-bs-toggle="tab" href="#level2-report" role="tab">Health Area (Level 2)</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="level3-report-tab" data-bs-toggle="tab" href="#level3-report" role="tab">Health Systems Building Blocks</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="funds-report-tab" data-bs-toggle="tab" href="#funds-report" role="tab">Type of Fund</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="fund-management-report-tab" data-bs-toggle="tab" href="#fund-management-report" role="tab">Fund & Management</a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content mt-3" id="reportTabContent">
                    <!-- Funding Sources Report -->
                    <div class="tab-pane fade show active" id="funding-sources-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Funding Sources - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="fundingBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Funding Sources - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="fundingPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Funding Sources - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Funding Source</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($fundingsources as $source)
                                                    <tr>
                                                        <td>{{ $source->funding_source }}</td>
                                                        <td class="text-center">{{ number_format($source->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($source->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($source->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($source->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Management Types Report -->
                    <div class="tab-pane fade" id="management-types-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Management Types - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="managementBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Management Types - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="managementPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Management Types - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Management Type</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($manages as $management)
                                                    <tr>
                                                        <td>{{ $management->management }}</td>
                                                        <td class="text-center">{{ number_format($management->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($management->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($management->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($management->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- DePDev Report -->
                    <div class="tab-pane fade" id="depdev-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">DePDev Classification - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="depdevBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">DePDev Classification - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="depdevPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">DePDev Classification - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>DePDev Classification</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($depdevs as $depdev)
                                                    <tr>
                                                        <td>{{ $depdev->depdev }}</td>
                                                        <td class="text-center">{{ number_format($depdev->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($depdev->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($depdev->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($depdev->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- GPH Report -->
                    <div class="tab-pane fade" id="gph-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Type of GPH Implemented - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="gphBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Type of GPH Implemented - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="gphPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Type of GPH Implemented - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Type of GPH Implemented</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($gphs as $gph)
                                                    <tr>
                                                        <td>{{ $gph->gph }}</td>
                                                        <td class="text-center">{{ number_format($gph->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($gph->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($gph->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($gph->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Level 1 Report -->
                    <div class="tab-pane fade" id="level1-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Area (Level 1) - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="level1BarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Area (Level 1) - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="level1PieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Area (Level 1) - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Health Area (Level 1)</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($level1s as $level1)
                                                    <tr>
                                                        <td>{{ $level1->level1 }}</td>
                                                        <td class="text-center">{{ number_format($level1->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($level1->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($level1->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($level1->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Level 2 Report -->
                    <div class="tab-pane fade" id="level2-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Area (Level 2) - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="level2BarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Area (Level 2) - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="level2PieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Area (Level 2) - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Health Area (Level 2)</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($level2s as $level2)
                                                    <tr>
                                                        <td>{{ $level2->level2 }}</td>
                                                        <td class="text-center">{{ number_format($level2->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($level2->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($level2->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($level2->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Level 3 Report -->
                    <div class="tab-pane fade" id="level3-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Systems Building Blocks - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="level3BarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Systems Building Blocks - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 450px;">
                                            <canvas id="level3PieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Health Systems Building Blocks - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Health Systems Building Blocks</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($level3s as $level3)
                                                    <tr>
                                                        <td>{{ $level3->level3 }}</td>
                                                        <td class="text-center">{{ number_format($level3->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($level3->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($level3->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($level3->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Funds Report -->
                    <div class="tab-pane fade" id="funds-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Type of Fund - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="fundsBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Type of Fund - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="fundsPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Type of Fund - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Type of Fund</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($funds as $fund)
                                                    <tr>
                                                        <td>{{ $fund->type_of_funds }}</td>
                                                        <td class="text-center">{{ number_format($fund->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($fund->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($fund->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($fund->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fund Management Report -->
                    <div class="tab-pane fade" id="fund-management-report" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Fund & Management - Bar Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="fundManagementBarChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Fund & Management - Pie Chart</h5>
                                    </div>
                                    <div class="card-body">
                                        <div style="height: 420px;">
                                            <canvas id="fundManagementPieChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Fund & Management - Data Table</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Fund & Management</th>
                                                        <th class="text-center">Project Count</th>
                                                        <th class="text-center">Total Budget</th>
                                                        <th class="text-center">Count %</th>
                                                        <th class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($fundmanagements as $fundmanagement)
                                                    <tr>
                                                        <td>{{ $fundmanagement->fund_and_management }}</td>
                                                        <td class="text-center">{{ number_format($fundmanagement->project_count, 0, '.', ',') }}</td>
                                                        <td class="text-center">₱{{ number_format($fundmanagement->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($fundmanagement->count_percentage, 2, '.', ',') }}%</td>
                                                        <td class="text-center">{{ number_format($fundmanagement->budget_percentage, 2, '.', ',') }}%</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script>
        // Register the datalabels plugin
        Chart.register(ChartDataLabels);
        // Color palette for charts
        const colors = [
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 206, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)',
            'rgba(199, 199, 199, 0.8)',
            'rgba(83, 102, 255, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(54, 162, 235, 0.8)'
        ];

        const borderColors = [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(199, 199, 199, 1)',
            'rgba(83, 102, 255, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)'
        ];

        // Common chart options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                datalabels: {
                    anchor: 'end',
                    align: 'top',
                    offset: 5,
                    color: '#000',
                    font: {
                        weight: 'bold',
                        size: 12
                    },
                    formatter: function(value, context) {
                        const parts = value.toString().split('.');
                        const wholePart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        return parts[1] ?
                            `₱${wholePart}.${parts[1]}` :
                            `₱${wholePart}`;
                    },
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] > 0;
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            if (context.dataset.type === 'bar') {
                                return `${label}: ₱${value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                            } else {
                                return `${label}: ${value.toFixed(2)}%`;
                            }
                        }
                    }
                }
            }
        };

        const barOptions = {
            ...commonOptions,
            // Increase chart area height by setting min/max size in layout
            layout: {
                padding: {
                    top: 20,
                    bottom: 20
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString('en-US');
                        }
                    }
                }
            },
            plugins: {
                ...commonOptions.plugins,
                datalabels: {
                    ...commonOptions.plugins.datalabels,
                    display: function(context) {
                        return context.dataset.data[context.dataIndex] > 0;
                    }
                }
            }
        };

        // Initialize charts when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Funding Sources Charts
            createCharts('funding', {!! json_encode($fundingsources) !!}, 'funding_source', 'total_budget');

            // Management Types Charts
            createCharts('management', {!! json_encode($manages) !!}, 'management', 'total_budget');

            // DePDev Charts
            createCharts('depdev', {!! json_encode($depdevs) !!}, 'depdev', 'total_budget');

            // GPH Charts
            createCharts('gph', {!! json_encode($gphs) !!}, 'gph', 'total_budget');

            // Level 1 Charts
            createCharts('level1', {!! json_encode($level1s) !!}, 'level1', 'total_budget');

            // Level 2 Charts
            createCharts('level2', {!! json_encode($level2s) !!}, 'level2', 'total_budget');

            // Level 3 Charts
            createCharts('level3', {!! json_encode($level3s) !!}, 'level3', 'total_budget');

            // Funds Charts
            createCharts('funds', {!! json_encode($funds) !!}, 'type_of_funds', 'total_budget');

            // Fund Management Charts
            createCharts('fundManagement', {!! json_encode($fundmanagements) !!}, 'fund_and_management', 'total_budget');
        });

        function createCharts(prefix, data, labelField, valueField) {
            const labels = data.map(item => item[labelField]);
            const values = data.map(item => {
                const val = Number(item[valueField]);
                return isNaN(val) ? 0 : val;
            });
            const percentages = data.map(item => {
                const val = Number(item.budget_percentage);
                return isNaN(val) ? 0 : val;
            });

            // Create bar chart
            const barCtx = document.getElementById(prefix + 'BarChart').getContext('2d');
            new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Budget',
                        data: values,
                        backgroundColor: 'rgba(41, 109, 152, 1)',
                        borderColor: 'rgba(41, 109, 152, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    ...barOptions,
                    plugins: {
                        ...barOptions.plugins,
                        datalabels: {
                            anchor: 'end',
                            align: 'top',
                            offset: 5,
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                console.log('Bar Datalabels value:', value, 'Context:', context);
                                if (typeof value !== 'number' || isNaN(value)) return '';
                                return '₱' + value.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            },
                            display: function(context) {
                                const v = context.dataset.data[context.dataIndex];
                                return typeof v === 'number' && v > 0;
                            }
                        }
                    }
                }
            });

            // Create pie chart
            const pieCtx = document.getElementById(prefix + 'PieChart').getContext('2d');
            new Chart(pieCtx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: percentages,
                        backgroundColor: colors.slice(0, labels.length),
                        borderColor: borderColors.slice(0, labels.length),
                        borderWidth: 2
                    }]
                },
                options: {
                    ...commonOptions,
                    layout: {
                        padding: {
                            top: 20,
                            bottom: 20
                        }
                    },
                    plugins: {
                        ...commonOptions.plugins,
                        datalabels: {
                            color: '#000',
                            font: {
                                weight: 'bold',
                                size: 11
                            },
                            formatter: function(value, context) {
                                console.log('Pie Datalabels value:', value, 'Context:', context);
                                if (typeof value !== 'number' || isNaN(value)) return '';
                                return value.toFixed(1) + '%';
                            },
                            display: function(context) {
                                const v = context.dataset.data[context.dataIndex];
                                return typeof v === 'number' && v > 0;
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const percentage = context.raw || 0;
                                    const index = context.dataIndex;
                                    const budget = values[index];
                                    return `${label}: ${typeof percentage === 'number' ? percentage.toFixed(2) : ''}% (₱${typeof budget === 'number' ? budget.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}) : ''})`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function printActiveTab() {
            // Get the active tab and its content
            const activeTab = document.querySelector('.tab-pane.active.show');
            if (!activeTab) {
                alert('No active tab to print!');
                return;
            }

            // Optionally, get the executive summary
            const execSummary = document.querySelector('.card.bg-light');

            // Clone the active tab to manipulate canvases
            const tabClone = activeTab.cloneNode(true);

            // Get all canvases in the original and the clone
            const originalCanvases = activeTab.querySelectorAll('canvas');
            const cloneCanvases = tabClone.querySelectorAll('canvas');

            // Replace each canvas in the clone with an image from the original
            originalCanvases.forEach((origCanvas, idx) => {
                try {
                    const img = document.createElement('img');
                    img.src = origCanvas.toDataURL('image/png');
                    img.style.display = 'block';
                    img.style.maxWidth = '100%';
                    img.style.margin = '0 auto';
                    img.style.paddingTop = '20px';
                    if (cloneCanvases[idx]) {
                        cloneCanvases[idx].parentNode.replaceChild(img, cloneCanvases[idx]);
                    }
                } catch (e) {
                    if (cloneCanvases[idx]) cloneCanvases[idx].remove();
                }
            });

            // Create a new window for printing
            const printWindow = window.open('', '', 'width=900,height=650');
            printWindow.document.write('<html><head><title>Print Report</title>');

            // Copy styles
            document.querySelectorAll('style, link[rel=stylesheet]').forEach(style => {
                printWindow.document.write(style.outerHTML);
            });

            printWindow.document.write('</head><body style="background: #fff;">');

            // Optionally print executive summary
            if (execSummary) {
                printWindow.document.write(execSummary.outerHTML);
            }

            // Print only the active tab content (with images instead of canvases)
            printWindow.document.write(tabClone.outerHTML);

            printWindow.document.write('</body></html>');
            printWindow.document.close();

            // Wait for content to load, then print
            printWindow.onload = function() {
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            };
        }
    </script>

    <style>
        @media print {
            .btn, .nav-tabs {
                display: none !important;
            }
            .card {
                border: 1px solid #000 !important;
                page-break-inside: avoid;
            }
            .tab-pane {
                display: block !important;
                page-break-after: always;
            }
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .nav-tabs .nav-link {
            color: #495057;
            border: 1px solid transparent;
            border-radius: 0.25rem 0.25rem 0 0;
        }

        .nav-tabs .nav-link.active {
            color: #495057;
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }

        /* Add top padding to chart containers */
        .card-body > .horizontal-scroll,
        .card-body > div > canvas,
        .card-body > div > .horizontal-scroll {
            padding-top: 20px;
        }
    </style>
@endif
</x-app-layout>
