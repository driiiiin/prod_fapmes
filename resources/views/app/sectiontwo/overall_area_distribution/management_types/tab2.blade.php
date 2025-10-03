<div class="tab-pane fade show active" id="funding-sources" role="tabpanel" aria-labelledby="funding-sources-tab">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-9 pt-4">

                <!-- 1st data: Funding Sources - Total Budget -->
                <div class="bar-chart-container">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-left mb-0"><strong>Management Types - Total Budget</strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- GRAPH -->
                                    @php
                                    $labels = [];
                                    $data = [];
                                    foreach ($manages as $manage) {
                                    $labels[] = $manage->management;
                                    $data[] = $manage->total_budget;
                                    }
                                    @endphp

                                    <div style="width: 100%; overflow-x: auto;">
                                        <canvas id="manageChart"></canvas>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctx = document.getElementById('manageChart').getContext('2d');
                                            const labels = @json($labels);
                                            const data = @json($data);

                                            const manageChart = new Chart(ctx, {
                                                type: 'bar',
                                                data: {
                                                    labels: labels,
                                                    datasets: [{
                                                        label: 'Total Budget',
                                                        data: data,
                                                        backgroundColor: 'rgba(41, 109, 152, 1)',
                                                        borderColor: 'rgba(41, 109, 152, 1)',
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
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
                                                            formatter: function(value) {
                                                                const parts = value.toString().split('.');
                                                                const wholePart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                                                return parts[1] ?
                                                                    `₱${wholePart}.${parts[1]}` :
                                                                    `₱${wholePart}`;
                                                            }
                                                        }
                                                    },
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true,
                                                            grid: {
                                                                display: false
                                                            },
                                                            ticks: {
                                                                callback: function(value) {
                                                                    const parts = value.toString().split('.');
                                                                    const wholePart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                                                    return parts[1] ?
                                                                        `₱${wholePart}.${parts[1]}` :
                                                                        `₱${wholePart}`;
                                                                }
                                                            }
                                                        },
                                                        x: {
                                                            grid: {
                                                                display: false
                                                            }
                                                        }
                                                    }
                                                },
                                            });
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 1st data: Funding Sources - Total Budget -->
                <div class="doughnut-chart-container" style="display: none;">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-left mb-0"><strong>Management Types - Total Budget</strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- GRAPH -->
                                    @php
                                    $labels = [];
                                    $data = [];
                                    $colors = [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)',
                                    'rgba(255, 0, 0, 1)',
                                    'rgba(0, 255, 0, 1)',
                                    'rgba(0, 0, 255, 1)',
                                    ];
                                    $i = 0;
                                    $totalBudget = 0;
                                    foreach ($manages as $manage) {
                                    $labels[] = $manage->management;
                                    $totalBudget += $manage->total_budget;
                                    $data[] = [
                                    'label' => $manage->management,
                                    'data' => $manage->total_budget,
                                    'backgroundColor' => $colors[$i++ % count($colors)],
                                    ];
                                    }
                                    $percentages = [];
                                    if ($totalBudget > 0) {
                                    $percentages = array_map(function ($item) use ($totalBudget) {
                                    return ($item['data'] / $totalBudget) * 100;
                                    }, $data);
                                    } else {
                                    $percentages = array_fill(0, count($data), 0); // Handle zero total budget
                                    }
                                    @endphp
                                    <div style="width: 400px; overflow-x: auto; margin: 0 auto;">
                                        <canvas id="manageChart2"></canvas>
                                    </div>
                                    <div style="text-align: center;">
                                        @foreach($percentages as $index => $percentage)
                                        <div style="display: inline-block; margin: 5px;">
                                            <span style="display: inline-block; width: 12px; height: 12px; background-color: {{ $colors[$index % count($colors)] }};"></span>
                                            {{ $labels[$index] }}: {{ number_format($percentage, 2) }}% ({{ number_format($data[$index]['data'], 2) }})
                                        </div>
                                        @endforeach
                                    </div>
                                    <style>
                                        #manageChart2 {
                                            filter: none !important;
                                        }
                                    </style>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctx2 = document.getElementById('manageChart2').getContext('2d');
                                            const manageChart2 = new Chart(ctx2, {
                                                type: 'doughnut',
                                                data: {
                                                    labels: @json($labels),
                                                    datasets: [{
                                                        data: @json($percentages),
                                                        backgroundColor: @json(array_column($data, 'backgroundColor')),
                                                        hoverBackgroundColor: @json(array_column($data, 'backgroundColor')),
                                                        hoverBorderColor: 'rgba(0, 0, 0, 1)',
                                                    }]
                                                },
                                                options: {
                                                    cutoutPercentage: 70,
                                                    aspectRatio: 1,
                                                    plugins: {
                                                        legend: {
                                                            display: false
                                                        },
                                                        tooltip: {
                                                            callbacks: {
                                                                label: function(context) {
                                                                    const label = context.label || '';
                                                                    const value = context.raw || 0;
                                                                    return `${label}: ${value.toFixed(2)}% (${context.dataset.data[context.dataIndex].toFixed(2)})`;
                                                                }
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
                    </div>
                </div>

                <!-- 2nd data: Funding Sources - Breakdown -->
                <div class="row pt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- TABLE -->

                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="manage-table" style="width: 100%;">
                                                <thead style="border-top: 1px solid #ccc;">
                                                    <tr>
                                                        <th scope="col">Management</th>
                                                        <th scope="col" class="text-center">Project Count</th>
                                                        <th scope="col" class="text-center">Total Budget</th>
                                                        <th scope="col" class="text-center">Count %</th>
                                                        <th scope="col" class="text-center">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($manages as $manage)
                                                    <tr>
                                                        <td>{{ $manage->management }}</td>
                                                        <td class="text-center">{{ $manage->project_count }}</td>
                                                        <td class="text-center">{{ number_format($manage->total_budget, 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format($manage->count_percentage, 2, '.', ',') . '%' }}</td>
                                                        <td class="text-center">{{ number_format($manage->budget_percentage, 2, '.', ',') . '%' }}</td>
                                                    </tr>
                                                    @empty
                                                    <!-- <tr>
                                                        <td colspan="5" class="text-center">No project found.</td>
                                                    </tr> -->
                                                    @endforelse
                                                    <tr style="background-color: #f5f5f5;">
                                                        <td colspan="1" class="text-end"><strong>Total:</strong></td>
                                                        <td class="text-center">{{ number_format(collect($manages)->sum('project_count'), 0, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format(collect($manages)->sum('total_budget'), 2, '.', ',') }}</td>
                                                        <td class="text-center">{{ number_format(collect($manages)->sum('count_percentage'), 2, '.', ',') . '%' }}</td>
                                                        <td class="text-center">{{ number_format(collect($manages)->sum('budget_percentage'), 2, '.', ',') . '%' }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    function updateTableColumns() {
                        const selectedValue = document.querySelector('input[name="subgroup"]:checked')?.value;
                        const fundingsColumn = document.querySelectorAll('.fundings-column');
                        const depdevColumn = document.querySelectorAll('.depdev-column');
                        const gphColumn = document.querySelectorAll('.gph-column');

                        // Hide Funding Sources, depdev and GPH columns first
                        fundingsColumn.forEach(col => col.style.display = 'none');
                        depdevColumn.forEach(col => col.style.display = 'none');
                        gphColumn.forEach(col => col.style.display = 'none');

                        // Show the selected column if a radio is checked
                        if (selectedValue === 'fundings') {
                            fundingsColumn.forEach(col => col.style.display = 'table-cell');
                        } else if (selectedValue === 'depdev') {
                            depdevColumn.forEach(col => col.style.display = 'table-cell');
                        } else if (selectedValue === 'gph') {
                            gphColumn.forEach(col => col.style.display = 'table-cell');
                        }
                    }

                    // Call updateTableColumns when the page loads
                    document.addEventListener('DOMContentLoaded', function() {
                        updateTableColumns();
                    });
                </script>

                <style>
                    .chart-container {
                        position: relative;
                        margin: auto;
                        height: 300px;
                        width: 100%;
                    }

                    .legend-item {
                        display: inline-block;
                        margin: 5px;
                    }

                    .legend-color {
                        display: inline-block;
                        width: 12px;
                        height: 12px;
                        margin-right: 5px;
                    }
                </style>

            </div>
            <div class="col-md-3 mt-4">
                @include('app.sectiontwo.overall_area_distribution.filter')
            </div>
        </div>
    </div>
</div>

