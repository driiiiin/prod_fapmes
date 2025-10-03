<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <style>
        .toggle-filter-btn {
            color: black;
            border: none;
            padding: 6px 10px;
            font-size: 14px;
            cursor: pointer;
        }
        .filter-section {
            display: none;
            transition: all 0.3s ease;
        }
        .filter-section.active {
            display: block;
        }
    </style>
    <div class="container-fluid mt-4 pt-4" style="width: 95%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-left mb-0"><strong>Health Area Distribution List</strong></h4>
                <div class="d-flex align-items-center" style="gap: 10px;">
                    <a href="{{ route('health_area_distribution.report') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" style="height: 38px;">
                        <i class="fa fa-bar-chart"></i> <span class="ms-1">Generate Report</span>
                    </a>
                    <button id="toggleFilterBtn" class="toggle-filter-btn">&#9881; Filter</button>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <div class="filter-section" id="filterSectionHAD">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0"><strong>Filters</strong></h6>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label for="funding_source_filter" class="form-label" style="font-size: small;"><strong>Funding Source:</strong></label>
                                            <select class="form-select border" id="funding_source_filter" style="font-size: small;">
                                                <option value="All">All</option>
                                                @foreach (DB::table('ref_funds')->select('funds_desc')->get() as $funding_source)
                                                <option value="{{ $funding_source->funds_desc }}">{{ $funding_source->funds_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="management_filter" class="form-label" style="font-size: small;"><strong>Management:</strong></label>
                                            <select class="form-select border" id="management_filter" style="font-size: small;">
                                                <option value="All">All</option>
                                                @foreach (DB::table('ref_management')->select('management_desc')->get() as $management)
                                                <option value="{{ $management->management_desc }}">{{ $management->management_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="level1_filter" class="form-label" style="font-size: small;"><strong>Health Area (Level 1):</strong></label>
                                            <select class="form-select border" id="level1_filter" style="font-size: small;">
                                                <option value="All">All</option>
                                                @foreach (DB::table('ref_level1')->select('level1_desc')->get() as $level1)
                                                <option value="{{ $level1->level1_desc }}">{{ $level1->level1_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="level2_filter" class="form-label" style="font-size: small;"><strong>Health Area (Level 2):</strong></label>
                                            <select class="form-select border" id="level2_filter" style="font-size: small;">
                                                <option value="All">All</option>
                                                @foreach (DB::table('ref_level2')->select('level2_desc')->get() as $level2)
                                                <option value="{{ $level2->level2_desc }}">{{ $level2->level2_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label for="level3_filter" class="form-label" style="font-size: small;"><strong>Health Systems Building Blocks:</strong></label>
                                            <select class="form-select border" id="level3_filter" style="font-size: small;">
                                                <option value="All">All</option>
                                                @foreach (DB::table('ref_level3')->select('level3_desc')->get() as $level3)
                                                <option value="{{ $level3->level3_desc }}">{{ $level3->level3_desc }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-2 mt-4 d-flex justify-content-center align-items-center">
                                            <div>
                                                <button type="button" class="btn btn-primary btn-sm" onclick="applyFilters()">
                                                    <i class="fa fa-filter"></i> Apply Filters
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm ms-2" onclick="resetFilters()">
                                                    <i class="fa fa-undo"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        @php
                            // Get accurate project counts for funding_source -> management using the correct method
                            $fundingManagementData = \App\Models\Project::getProjectCountByFundingSourceAndManagement();

                            // Get accurate level data for management -> levels flows using the correct method
                            $levelData = \App\Models\Project::getProjectCountByManagementAndLevels();

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
                                        $sankeyDataLevel1[$key2] = $row->project_count; // Use direct assignment instead of addition
                                    }

                                    // management -> level2 (if exists)
                                    if ($row->level2) {
                                        $key3 = $row->management . '||' . $row->level2;
                                        $sankeyDataLevel2[$key3] = $row->project_count; // Use direct assignment instead of addition
                                    }

                                    // management -> level3 (if exists)
                                    if ($row->level3) {
                                        $key4 = $row->management . '||' . $row->level3;
                                        $sankeyDataLevel3[$key4] = $row->project_count; // Use direct assignment instead of addition
                                    }
                                }
                            }

                            // Convert to data rows for each chart
                            $dataRowsLevel1 = [];
                            foreach ($sankeyDataLevel1 as $key => $count) {
                                [$from, $to] = explode('||', $key);
                                $from = addslashes($from);
                                $to = addslashes($to);
                                $dataRowsLevel1[] = "['{$from}', '{$to}', {$count}]";
                            }
                            $dataRowsStringLevel1 = implode(",\n", $dataRowsLevel1);

                            $dataRowsLevel2 = [];
                            foreach ($sankeyDataLevel2 as $key => $count) {
                                [$from, $to] = explode('||', $key);
                                $from = addslashes($from);
                                $to = addslashes($to);
                                $dataRowsLevel2[] = "['{$from}', '{$to}', {$count}]";
                            }
                            $dataRowsStringLevel2 = implode(",\n", $dataRowsLevel2);

                            $dataRowsLevel3 = [];
                            foreach ($sankeyDataLevel3 as $key => $count) {
                                [$from, $to] = explode('||', $key);
                                $from = addslashes($from);
                                $to = addslashes($to);
                                $dataRowsLevel3[] = "['{$from}', '{$to}', {$count}]";
                            }
                            $dataRowsStringLevel3 = implode(",\n", $dataRowsLevel3);
                        @endphp

                        <!-- Level 1 Sankey Chart -->
                        <div class="mb-5 mt-3">
                            <h5 class="text-center mb-3"><strong>Health Area (Level 1) Project Distribution</strong></h5>
                            <div style="width: 100%; max-width: 1050px; margin: 0 auto;">
                                <div id="sankey_chart_level1" style="height: 500px;"></div>
                            </div>
                        </div>

                        <!-- Level 2 Sankey Chart -->
                        <div class="mb-5">
                            <h5 class="text-center mb-3"><strong>Health Area (Level 2) Project Distribution</strong></h5>
                            <div style="width: 100%; max-width: 1050px; margin: 0 auto;">
                                <div id="sankey_chart_level2" style="height: 500px;"></div>
                            </div>
                        </div>

                        <!-- Level 3 Sankey Chart -->
                        <div class="mb-5">
                            <h5 class="text-center mb-3"><strong>Health Systems Building Blocks Project Distribution</strong></h5>
                            <div style="width: 100%; max-width: 1050px; margin: 0 auto;">
                                <div id="sankey_chart_level3" style="height: 500px;"></div>
                            </div>
                        </div>

                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        <script type="text/javascript">
                            google.charts.load('current', {
                                'packages': ['sankey']
                            });
                            google.charts.setOnLoadCallback(function() {
                                drawChartLevel1();
                                drawChartLevel2();
                                drawChartLevel3();
                            });

                            // Store initial data for reset functionality
                            let initialDataLevel1 = [
{!! $dataRowsStringLevel1 !!}
                            ];
                            let initialDataLevel2 = [
{!! $dataRowsStringLevel2 !!}
                            ];
                            let initialDataLevel3 = [
{!! $dataRowsStringLevel3 !!}
                            ];

                            function drawChartLevel1(data = null) {
                                var chartData = new google.visualization.DataTable();
                                chartData.addColumn('string', 'From');
                                chartData.addColumn('string', 'To');
                                chartData.addColumn('number', 'Project Count');

                                if (data) {
                                    chartData.addRows(data);
                                } else {
                                    chartData.addRows(initialDataLevel1);
                                }

                                var options = {
                                    width: '100%',
                                    height: 500,
                                    sankey: {
                                        node: {
                                            label: {
                                                fontSize: 12
                                            }
                                        }
                                    }
                                };

                                var chart = new google.visualization.Sankey(document.getElementById('sankey_chart_level1'));
                                chart.draw(chartData, options);
                            }

                            function drawChartLevel2(data = null) {
                                var chartData = new google.visualization.DataTable();
                                chartData.addColumn('string', 'From');
                                chartData.addColumn('string', 'To');
                                chartData.addColumn('number', 'Project Count');

                                if (data) {
                                    chartData.addRows(data);
                                } else {
                                    chartData.addRows(initialDataLevel2);
                                }

                                var options = {
                                    width: '100%',
                                    height: 500,
                                    sankey: {
                                        node: {
                                            label: {
                                                fontSize: 12
                                            }
                                        }
                                    }
                                };

                                var chart = new google.visualization.Sankey(document.getElementById('sankey_chart_level2'));
                                chart.draw(chartData, options);
                            }

                            function drawChartLevel3(data = null) {
                                var chartData = new google.visualization.DataTable();
                                chartData.addColumn('string', 'From');
                                chartData.addColumn('string', 'To');
                                chartData.addColumn('number', 'Project Count');

                                if (data) {
                                    chartData.addRows(data);
                                } else {
                                    chartData.addRows(initialDataLevel3);
                                }

                                var options = {
                                    width: '100%',
                                    height: 500,
                                    sankey: {
                                        node: {
                                            label: {
                                                fontSize: 12
                                            }
                                        }
                                    }
                                };

                                var chart = new google.visualization.Sankey(document.getElementById('sankey_chart_level3'));
                                chart.draw(chartData, options);
                            }

                            function applyFilters() {
                                const fundingSource = document.getElementById('funding_source_filter').value;
                                const management = document.getElementById('management_filter').value;
                                const level1 = document.getElementById('level1_filter').value;
                                const level2 = document.getElementById('level2_filter').value;
                                const level3 = document.getElementById('level3_filter').value;

                                // Show loading state
                                document.getElementById('sankey_chart_level1').innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
                                document.getElementById('sankey_chart_level2').innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
                                document.getElementById('sankey_chart_level3').innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';

                                // Fetch filtered data
                                fetch('/health-areas-distribution/filtered-data', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        funding_source: fundingSource,
                                        management: management,
                                        level1: level1,
                                        level2: level2,
                                        level3: level3
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    // Update charts with filtered data
                                    drawChartLevel1(data.level1);
                                    drawChartLevel2(data.level2);
                                    drawChartLevel3(data.level3);
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    // Reset to initial data on error
                                    drawChartLevel1();
                                    drawChartLevel2();
                                    drawChartLevel3();
                                });
                            }

                            function resetFilters() {
                                // Reset filter dropdowns
                                document.getElementById('funding_source_filter').value = 'All';
                                document.getElementById('management_filter').value = 'All';
                                document.getElementById('level1_filter').value = 'All';
                                document.getElementById('level2_filter').value = 'All';
                                document.getElementById('level3_filter').value = 'All';

                                // Reset charts to initial data
                                drawChartLevel1();
                                drawChartLevel2();
                                drawChartLevel3();
                            }

                            // Dynamic filtering for Level 2 based on Level 1 selection
                            document.getElementById('level1_filter').addEventListener('change', function() {
                                const level1Value = this.value;
                                const level2Select = document.getElementById('level2_filter');
                                const level3Select = document.getElementById('level3_filter');

                                // Reset Level 2 and Level 3 when Level 1 changes
                                level2Select.innerHTML = '<option value="All">All</option>';
                                level3Select.innerHTML = '<option value="All">All</option>';

                                if (level1Value !== 'All') {
                                    // Fetch Level 2 options for selected Level 1
                                    fetch(`/get-level2-options?level1=${encodeURIComponent(level1Value)}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            data.forEach(option => {
                                                const optionElement = document.createElement('option');
                                                optionElement.value = option.level2_desc;
                                                optionElement.textContent = option.level2_desc;
                                                level2Select.appendChild(optionElement);
                                            });
                                        })
                                        .catch(error => {
                                            console.error('Error fetching Level 2 options:', error);
                                        });
                                }
                            });

                            // Dynamic filtering for Level 3 based on Level 2 selection
                            document.getElementById('level2_filter').addEventListener('change', function() {
                                const level2Value = this.value;
                                const level3Select = document.getElementById('level3_filter');

                                // Reset Level 3 when Level 2 changes
                                level3Select.innerHTML = '<option value="All">All</option>';

                                if (level2Value !== 'All') {
                                    // Fetch Level 3 options for selected Level 2
                                    fetch(`/get-level3-options?level2=${encodeURIComponent(level2Value)}`)
                                        .then(response => response.json())
                                        .then(data => {
                                            data.forEach(option => {
                                                const optionElement = document.createElement('option');
                                                optionElement.value = option.level3_desc;
                                                optionElement.textContent = option.level3_desc;
                                                level3Select.appendChild(optionElement);
                                            });
                                        })
                                        .catch(error => {
                                            console.error('Error fetching Level 3 options:', error);
                                        });
                                }
                            });

                            // Redraw charts on window resize
                            window.addEventListener('resize', function() {
                                drawChartLevel1();
                                drawChartLevel2();
                                drawChartLevel3();
                            });

                            document.getElementById('toggleFilterBtn').addEventListener('click', function() {
                                var filterSection = document.getElementById('filterSectionHAD');
                                filterSection.classList.toggle('active');
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
</x-app-layout>
