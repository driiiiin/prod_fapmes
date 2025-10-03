<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <div class="container-fluid mt-4 pt-4" style="width: 95%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-left mb-0"><strong>Health Area Distribution - Comprehensive Report</strong></h4>
                <a href="{{ route('health_area_distribution') }}" class="btn btn-secondary btn-sm no-print" style="float: right;">
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
                                    <button class="btn btn-primary btn-sm no-print" style="font-size: 0.95rem; padding: 0.4rem 1rem;" onclick="printActiveTab()">
                                        <i class="bi bi-printer"></i> Print Report
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Level 1 Report -->
                <div class="mb-5">
                    <h4 class="mb-3">Health Area (Level 1) - Sankey Diagram</h4>
                    <div style="width: 100%; max-width: 1050px; margin: 0 auto;">
                        <div id="sankey_chart_level1_report" style="height: 500px;"></div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Health Area (Level 1) - Data Table</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th class="text-center">Project Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataRowsLevel1 as $row)
                                        <tr>
                                            <td>{{ $row[0] }}</td>
                                            <td>{{ $row[1] }}</td>
                                            <td class="text-center">{{ number_format($row[2], 0, '.', ',') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Level 2 Report -->
                <div class="mb-5">
                    <h4 class="mb-3">Health Area (Level 2) - Sankey Diagram</h4>
                    <div style="width: 100%; max-width: 1050px; margin: 0 auto;">
                        <div id="sankey_chart_level2_report" style="height: 500px;"></div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Health Area (Level 2) - Data Table</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th class="text-center">Project Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataRowsLevel2 as $row)
                                        <tr>
                                            <td>{{ $row[0] }}</td>
                                            <td>{{ $row[1] }}</td>
                                            <td class="text-center">{{ number_format($row[2], 0, '.', ',') }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Level 3 Report -->
                <div class="mb-5">
                    <h4 class="mb-3">Health Systems Building Blocks - Sankey Diagram</h4>
                    <div style="width: 100%; max-width: 1050px; margin: 0 auto;">
                        <div id="sankey_chart_level3_report" style="height: 500px;"></div>
                    </div>
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Health Systems Building Blocks - Data Table</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>From</th>
                                            <th>To</th>
                                            <th class="text-center">Project Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataRowsLevel3 as $row)
                                        <tr>
                                            <td>{{ $row[0] }}</td>
                                            <td>{{ $row[1] }}</td>
                                            <td class="text-center">{{ number_format($row[2], 0, '.', ',') }}</td>
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

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['sankey']
        });
        google.charts.setOnLoadCallback(function() {
            drawChartLevel1Report();
            drawChartLevel2Report();
            drawChartLevel3Report();
        });

        // Convert PHP data to JavaScript arrays
        let dataRowsLevel1 = @json($dataRowsLevel1);
        let dataRowsLevel2 = @json($dataRowsLevel2);
        let dataRowsLevel3 = @json($dataRowsLevel3);

        function drawChartLevel1Report() {
            var chartData = new google.visualization.DataTable();
            chartData.addColumn('string', 'From');
            chartData.addColumn('string', 'To');
            chartData.addColumn('number', 'Project Count');

            chartData.addRows(dataRowsLevel1);

            var options = {
                width: 1050,
                height: 500,
                sankey: {
                    node: {
                        label: {
                            fontSize: 12
                        }
                    }
                }
            };

            var chart = new google.visualization.Sankey(document.getElementById('sankey_chart_level1_report'));
            chart.draw(chartData, options);
        }

        function drawChartLevel2Report() {
            var chartData = new google.visualization.DataTable();
            chartData.addColumn('string', 'From');
            chartData.addColumn('string', 'To');
            chartData.addColumn('number', 'Project Count');

            chartData.addRows(dataRowsLevel2);

            var options = {
                width: 1050,
                height: 500,
                sankey: {
                    node: {
                        label: {
                            fontSize: 12
                        }
                    }
                }
            };

            var chart = new google.visualization.Sankey(document.getElementById('sankey_chart_level2_report'));
            chart.draw(chartData, options);
        }

        function drawChartLevel3Report() {
            var chartData = new google.visualization.DataTable();
            chartData.addColumn('string', 'From');
            chartData.addColumn('string', 'To');
            chartData.addColumn('number', 'Project Count');

            chartData.addRows(dataRowsLevel3);

            var options = {
                width: 1050,
                height: 500,
                sankey: {
                    node: {
                        label: {
                            fontSize: 12
                        }
                    }
                }
            };

            var chart = new google.visualization.Sankey(document.getElementById('sankey_chart_level3_report'));
            chart.draw(chartData, options);
        }

        function printActiveTab() {
            // Print the entire report content (all levels)
            var reportContent = document.querySelector('.container-fluid.mt-4.pt-4');
            if (reportContent) {
                var printWindow = window.open('', '_blank');
                printWindow.document.write('<html><head><title>Health Area Distribution Report</title>');
                printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">');
                printWindow.document.write('<style>');
                printWindow.document.write('body { font-family: Arial, sans-serif; }');
                printWindow.document.write('.table { width: 100%; border-collapse: collapse; }');
                printWindow.document.write('.table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }');
                printWindow.document.write('.table th { background-color: #f2f2f2; }');
                printWindow.document.write('@media print { .no-print { display: none; } }');
                printWindow.document.write('</style>');
                printWindow.document.write('</head><body>');
                printWindow.document.write('<div class="container-fluid">');
                printWindow.document.write('<h2 class="text-center mb-4">Health Area Distribution Report</h2>');
                printWindow.document.write('<p class="text-center mb-4">Generated on: ' + new Date().toLocaleDateString() + '</p>');
                printWindow.document.write(reportContent.innerHTML);
                printWindow.document.write('</div>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            }
        }

        // Redraw charts on window resize
        window.addEventListener('resize', function() {
            drawChartLevel1Report();
            drawChartLevel2Report();
            drawChartLevel3Report();
        });
    </script>
@endif
</x-app-layout>
