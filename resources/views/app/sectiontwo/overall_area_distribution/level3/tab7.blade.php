<div class="tab-pane fade show active" id="level3" role="tabpanel" aria-labelledby="level3-tab">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-9 pt-4">

                <!-- 1st data: Health Systems Building Blocks- Total Budget -->
                <div class="bar-chart-container">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-left mb-0"><strong>Health Systems Building Blocks - Total Budget</strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- GRAPH -->
                                    @php
                                    $labels = [];
                                    $data = [];

                                    foreach ($level3s as $level3) {
                                    $labels[] = $level3->level3;
                                    $data[] = $level3->total_budget;
                                    }
                                    @endphp

                                    <div style="width: 100%; overflow-x: auto;">
                                        <canvas id="level3Chart"></canvas>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctx = document.getElementById('level3Chart').getContext('2d');
                                            const level3Chart = new Chart(ctx, {
                                                type: 'bar',
                                                data: {
                                                    labels: @json($labels),
                                                    datasets: [{
                                                        label: 'Total Budget',
                                                        data: @json($data),
                                                        backgroundColor: 'rgba(41, 109, 152, 1)',
                                                        borderColor: 'rgba(41, 109, 152, 1)',
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    scales: {
                                                        y: {
                                                            beginAtZero: true,
                                                            grid: {
                                                                display: false
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

                <!-- 1st data: Health Systems Building Blocks - Total Budget -->
                <div class="doughnut-chart-container" style="display: none;">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-left mb-0"><strong>Health Systems Building Blocks - Total Budget</strong></h4>
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
                                    foreach ($level3s as $level3) {
                                    $labels[] = $level3->level3;
                                    $totalBudget += $level3->total_budget;
                                    $data[] = [
                                    'label' => $level3->level3,
                                    'data' => $level3->total_budget,
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
                                        <canvas id="level3Chart2"></canvas>
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
                                        #level3Chart2 {
                                            filter: none !important;
                                        }
                                    </style>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctx2 = document.getElementById('level3Chart2').getContext('2d');
                                            const level3Chart2 = new Chart(ctx2, {
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

                <!-- 2nd data: level3 - Breakdown -->
                <div class="row pt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- TABLE -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="level3-table" style="width: 100%;">
                                                <thead style="border-top: 1px solid #ccc;">
                                                    <tr>
                                                        <th scope="col">Health Systems Building Blocks</th>
                                                        <th scope="col">Project Count</th>
                                                        <th scope="col">Total Budget</th>
                                                        <th scope="col">Count %</th>
                                                        <th scope="col">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($level3s as $level3)
                                                    <tr>
                                                        <td>{{ $level3->level3 }}</td>
                                                        <td class="text-center">{{ $level3->project_count }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($level3->total_budget, 2, '.', ',') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($level3->count_percentage, 2, '.', ',') . '%' }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($level3->budget_percentage, 2, '.', ',') . '%' }}
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <!-- <tr>
                                                        <td colspan="5" class="text-center">No project found.</td>
                                                    </tr> -->
                                                    @endforelse
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <td class="text-center"><strong>{{ number_format(collect($level3s)->sum('project_count'), 0, '.', ',') }}</strong></td>
                                                        <td class="text-center"><strong>{{ number_format(collect($level3s)->sum('total_budget'), 2, '.', ',') }}</strong></td>
                                                        <td class="text-center"><strong>{{ number_format(collect($level3s)->sum('count_percentage'), 2, '.', ',') . '%' }}</strong></td>
                                                        <td class="text-center"><strong>{{ number_format(collect($level3s)->sum('budget_percentage'), 2, '.', ',') . '%' }}</strong></td>
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
            </div>
            <div class="col-md-3 mt-4">
                @include('app.sectiontwo.overall_area_distribution.filter')
            </div>
        </div>
    </div>
</div>
