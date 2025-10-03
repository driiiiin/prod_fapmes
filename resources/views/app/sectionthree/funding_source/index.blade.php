<x-app-layout>
    <div class="container-fluid mt-4 pt-4" style="width: 90%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-left mb-0"><strong>Funding Source Distribution</strong></h4>
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="font-size: 14px;">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="area-chart-tab" data-bs-toggle="tab" href="#area-chart" role="tab" aria-controls="area-chart" aria-selected="true">Area Chart</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="data-table-tab" data-bs-toggle="tab" href="#data-table" role="tab" aria-controls="data-table" aria-selected="false">Data Table</a>
                    </li>
                </ul>
            </div>
            <div class="card-body pt-10">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="area-chart" role="tabpanel" aria-labelledby="area-chart-tab">
                        <div class="row">
                            <div class="col-md-9">
                                @php
                                    $fundingSources = DB::table('ref_funds')->select('funds_code')->get();
                                    $years = $projects->pluck('year')->unique()->sort()->values()->toArray();

                                    // Prepare data for the area chart
                                    $chartData = [];
                                    $headerRow = ['Year'];
                                    foreach($fundingSources as $source) {
                                        $headerRow[] = $source->funds_code;
                                    }
                                    $chartData[] = $headerRow;

                                    foreach($years as $year) {
                                        $row = [$year];
                                        foreach($fundingSources as $source) {
                                            $count = $projects->where('year', $year)
                                                ->where('donor', $source->funds_code)
                                                ->sum('project_count');
                                            $row[] = (int)$count;
                                        }
                                        $chartData[] = $row;
                                    }
                                @endphp

                                <div style="width: 100%; max-width: 1050px;">
                                    <div id="area_chart" style="height: 650px;"></div>
                                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                    <script type="text/javascript">
                                        google.charts.load('current', {'packages':['corechart']});
                                        google.charts.setOnLoadCallback(drawChart);

                                        function drawChart() {
                                            var data = google.visualization.arrayToDataTable(@json($chartData));

                                            var options = {
                                                isStacked: true,
                                                width: '100%',
                                                height: 650,
                                                legend: { position: 'right' },
                                                vAxis: { title: 'Number of Projects' },
                                                hAxis: {
                                                    title: 'Year',
                                                    format: '####' // Removes comma from year numbers
                                                },
                                                areaOpacity: 0.8,
                                                colors: ['#296D98', '#4CAF50', '#FFC107', '#F44336', '#9C27B0', '#00BCD4', '#FF9800', '#795548', '#607D8B']
                                            };

                                            var chart = new google.visualization.AreaChart(document.getElementById('area_chart'));
                                            chart.draw(data, options);

                                            window.addEventListener('resize', function() {
                                                chart.draw(data, options);
                                            });
                                        }
                                    </script>
                                </div>
                            </div>
                            <div class="col-md-3 mt-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Filter</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="year" class="form-label" style="font-size: small;"><strong>Year:</strong></label>
                                                <select class="form-select border" id="year" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach($years as $year)
                                                        <option value="{{ $year }}">{{ $year }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="funding_source" class="form-label" style="font-size: small;"><strong>Funding Source:</strong></label>
                                                <select class="form-select border" id="funding_source" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach($fundingSources as $source)
                                                        <option value="{{ $source->funds_code }}">{{ $source->funds_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="data-table" role="tabpanel" aria-labelledby="data-table-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="funding_source_datatable">
                                <thead>
                                    <tr>
                                        <th>Project ID</th>
                                        <th>Year</th>
                                        <th>Donor</th>
                                        <th>Project Count</th>
                                        <th>Total Budget</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $project->project_id }}</td>
                                            <td>{{ $project->year }}</td>
                                            <td>{{ $project->donor }}</td>
                                            <td>{{ $project->project_count }}</td>
                                            <td>{{ number_format($project->total_budget, 2) }}</td>
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

    <!-- Add DataTables CSS and JS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#funding_source_datatable').DataTable({
                pageLength: 10,
                order: [[1, 'asc']], // Sort by Year column by default
                responsive: true,
                language: {
                    search: "Search records:"
                }
            });
        });
    </script>
</x-app-layout>
