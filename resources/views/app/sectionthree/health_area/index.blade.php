<x-app-layout>
    <div class="container-fluid mt-4 pt-4" style="width: 90%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-left mb-0"><strong>Health Area Distribution</strong></h4>
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="font-size: 14px;">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="area-chart-tab" data-bs-toggle="tab" href="#area-chart" role="tab" aria-controls="area-chart" aria-selected="true">Stacked Area Chart</a>
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
                                    $healthAreas = DB::table('ref_level1')->select('level1_desc')->get();
                                    $years = $projects->pluck('year')->unique()->sort()->values()->toArray();

                                    // Prepare data for the stacked area chart
                                    $chartData = [];
                                    $headerRow = ['Year'];
                                    foreach($healthAreas as $area) {
                                        $headerRow[] = $area->level1_desc;
                                    }
                                    $chartData[] = $headerRow;

                                    foreach($years as $year) {
                                        $row = [$year];
                                        foreach($healthAreas as $area) {
                                            $count = $projects->where('year', $year)
                                                ->where('level1', $area->level1_desc)
                                                ->sum('project_count');
                                            $row[] = (int)$count;
                                        }
                                        $chartData[] = $row;
                                    }
                                @endphp

                                <div style="width: 100%; max-width: 1050px;">
                                    <div id="area_chart" style="height: 650px; position: relative;"></div>
                                    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                    <script type="text/javascript">
                                        // Store the original chart data for filtering
                                        var originalChartData = @json($chartData);

                                        google.charts.load('current', {'packages':['corechart','annotationchart']});
                                        google.charts.setOnLoadCallback(drawChart);

                                        function filterChartData(year, healthArea) {
                                            // year: string or number, healthArea: string
                                            // originalChartData: [ [header], [row1], [row2], ... ]
                                            var header = originalChartData[0];
                                            var filteredData = [header];

                                            // Determine which columns to keep based on healthArea
                                            var areaIndexes = [];
                                            if (healthArea === "All") {
                                                for (var i = 1; i < header.length; i++) {
                                                    areaIndexes.push(i);
                                                }
                                            } else {
                                                for (var i = 1; i < header.length; i++) {
                                                    if (header[i] === healthArea) {
                                                        areaIndexes.push(i);
                                                        break;
                                                    }
                                                }
                                            }

                                            // Filter rows by year
                                            for (var r = 1; r < originalChartData.length; r++) {
                                                var row = originalChartData[r];
                                                if (year === "All" || row[0].toString() === year.toString()) {
                                                    // Always keep year column
                                                    var newRow = [row[0]];
                                                    // Only keep selected health area(s)
                                                    for (var idx of areaIndexes) {
                                                        newRow.push(row[idx]);
                                                    }
                                                    filteredData.push(newRow);
                                                }
                                            }

                                            // Adjust header for health area filter
                                            var newHeader = [header[0]];
                                            for (var idx of areaIndexes) {
                                                newHeader.push(header[idx]);
                                            }
                                            filteredData[0] = newHeader;

                                            return filteredData;
                                        }

                                        function drawChart() {
                                            // Get current filter values
                                            var year = document.getElementById('year') ? document.getElementById('year').value : "All";
                                            var healthArea = document.getElementById('health_area') ? document.getElementById('health_area').value : "All";
                                            var rawData = filterChartData(year, healthArea);

                                            var data = google.visualization.arrayToDataTable(rawData);

                                            // Add data labels by creating a DataView and adding annotations
                                            var view = new google.visualization.DataView(data);

                                            // Build columns for annotations (data labels)
                                            var columns = [];
                                            columns.push(0); // Year column
                                            for (var i = 1; i < data.getNumberOfColumns(); i++) {
                                                columns.push(i);
                                                columns.push({
                                                    calc: "stringify",
                                                    sourceColumn: i,
                                                    type: "string",
                                                    role: "annotation"
                                                });
                                            }
                                            view.setColumns(columns);

                                            var colorPalette = ['#296D98', '#4CAF50', '#FFC107', '#F44336', '#9C27B0', '#00BCD4', '#FF9800', '#795548', '#607D8B'];
                                            // If only one health area, use a single color
                                            var colors = colorPalette.slice(0, data.getNumberOfColumns() - 1);

                                            var options = {
                                                isStacked: 'absolute',
                                                width: '100%',
                                                height: 650,
                                                legend: { position: 'right' },
                                                vAxis: { title: 'Number of Projects' },
                                                hAxis: {
                                                    title: 'Year',
                                                    format: '####'
                                                },
                                                areaOpacity: 0.8,
                                                tooltip: { isHtml: true, trigger: 'focus' },
                                                focusTarget: 'category',
                                                colors: colors,
                                                annotations: {
                                                    alwaysOutside: false,
                                                    textStyle: {
                                                        fontSize: 12,
                                                        color: '#222',
                                                        auraColor: 'none'
                                                    }
                                                },
                                                chartArea: { left: 60, top: 40, width: '75%', height: '80%' }
                                            };

                                            var chart = new google.visualization.AreaChart(document.getElementById('area_chart'));
                                            chart.draw(view, options);

                                            // Redraw on resize
                                            window.addEventListener('resize', function() {
                                                chart.draw(view, options);
                                            });

                                            // Add custom hover for details at a glance
                                            google.visualization.events.addListener(chart, 'onmouseover', function(e) {
                                                var row = e.row;
                                                var col = e.column;
                                                if (row !== null && col !== null && col > 0 && col % 2 === 1) {
                                                    var year = data.getValue(row, 0);
                                                    var area = data.getColumnLabel(col);
                                                    var value = data.getValue(row, col);
                                                    var tooltipHtml = '<div style="padding:8px 12px;"><strong>Year:</strong> ' + year + '<br><strong>Health Area:</strong> ' + area + '<br><strong>Projects:</strong> ' + value + '</div>';
                                                    var tooltipDiv = document.getElementById('custom-tooltip');
                                                    if (!tooltipDiv) {
                                                        tooltipDiv = document.createElement('div');
                                                        tooltipDiv.id = 'custom-tooltip';
                                                        tooltipDiv.style.position = 'absolute';
                                                        tooltipDiv.style.background = 'rgba(255,255,255,0.95)';
                                                        tooltipDiv.style.border = '1px solid #ccc';
                                                        tooltipDiv.style.borderRadius = '4px';
                                                        tooltipDiv.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
                                                        tooltipDiv.style.pointerEvents = 'none';
                                                        tooltipDiv.style.zIndex = 1000;
                                                        tooltipDiv.style.fontSize = '14px';
                                                        tooltipDiv.style.display = 'none';
                                                        document.getElementById('area_chart').appendChild(tooltipDiv);
                                                    }
                                                    tooltipDiv.innerHTML = tooltipHtml;
                                                    tooltipDiv.style.display = 'block';
                                                    // Position tooltip near mouse
                                                    document.getElementById('area_chart').onmousemove = function(ev) {
                                                        tooltipDiv.style.left = (ev.offsetX + 20) + 'px';
                                                        tooltipDiv.style.top = (ev.offsetY - 10) + 'px';
                                                    };
                                                }
                                            });
                                            google.visualization.events.addListener(chart, 'onmouseout', function(e) {
                                                var tooltipDiv = document.getElementById('custom-tooltip');
                                                if (tooltipDiv) {
                                                    tooltipDiv.style.display = 'none';
                                                }
                                            });
                                        }

                                        // Listen for filter changes
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var yearSelect = document.getElementById('year');
                                            var areaSelect = document.getElementById('health_area');
                                            if (yearSelect) {
                                                yearSelect.addEventListener('change', function() {
                                                    drawChart();
                                                });
                                            }
                                            if (areaSelect) {
                                                areaSelect.addEventListener('change', function() {
                                                    drawChart();
                                                });
                                            }
                                        });
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
                                                <label for="health_area" class="form-label" style="font-size: small;"><strong>Health Area:</strong></label>
                                                <select class="form-select border" id="health_area" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach($healthAreas as $area)
                                                        <option value="{{ $area->level1_desc }}">{{ $area->level1_desc }}</option>
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
                            <table class="table table-bordered table-hover" id="health_area_datatable">
                                <thead>
                                    <tr>
                                        <th>Project ID</th>
                                        <th>Year</th>
                                        <th>Health Area</th>
                                        <th>Project Count</th>
                                        <th>Total Budget</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($projects as $project)
                                        <tr>
                                            <td>{{ $project->project_id }}</td>
                                            <td>{{ $project->year }}</td>
                                            <td>{{ $project->level1 }}</td>
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
            var table = $('#health_area_datatable').DataTable({
                pageLength: 10,
                order: [[1, 'asc']], // Sort by Year column by default
                responsive: true,
                language: {
                    search: "Search records:"
                }
            });

            // Custom filtering for DataTable
            function filterTable() {
                var year = $('#year').val();
                var area = $('#health_area').val();

                table.rows().every(function() {
                    var data = this.data();
                    var show = true;
                    if (year !== "All" && data[1] != year) {
                        show = false;
                    }
                    if (area !== "All" && data[2] != area) {
                        show = false;
                    }
                    if (show) {
                        $(this.node()).show();
                    } else {
                        $(this.node()).hide();
                    }
                });
            }

            $('#year, #health_area').on('change', function() {
                filterTable();
            });
        });
    </script>
</x-app-layout>
