<div class="tab-pane fade show active" id="type-of-funds" role="tabpanel" aria-labelledby="type-of-funds-tab">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-9 pt-4">

                <!-- 1st data: Type of Funds - Total Budget -->
                <div class="bar-chart-container">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-left mb-0"><strong>Type of Funds - Total Budget</strong></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- GRAPH -->
                                    @php
                                    $labels = [];
                                    $data = [];
                                    foreach ($funds as $fund) {
                                    $labels[] = $fund->type_of_funds;
                                    $data[] = $fund->total_budget;
                                    }
                                    @endphp

                                    <div style="width: 100%; overflow-x: auto;">
                                        <canvas id="typeOfFundsChart"></canvas>
                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctx = document.getElementById('typeOfFundsChart').getContext('2d');

                                            // Format values consistently (whole numbers with commas, decimals as-is)
                                            function formatCurrency(value) {
                                                const parts = value.toString().split('.');
                                                const wholePart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                                return parts[1] ? `₱${wholePart}.${parts[1]}` : `₱${wholePart}`;
                                            }

                                            const typeOfFundsChart = new Chart(ctx, {
                                                type: 'bar',
                                                data: {
                                                    labels: {!! json_encode($labels) !!},
                                                    datasets: [{
                                                        label: 'Total Budget',
                                                        data: {!! json_encode($data) !!},
                                                        backgroundColor: 'rgba(41, 109, 152, 1)',
                                                        borderColor: 'rgba(41, 109, 152, 1)',
                                                        borderWidth: 1
                                                    }]
                                                },
                                                options: {
                                                    responsive: true,
                                                    maintainAspectRatio: false,
                                                    plugins: {
                                                        legend: {
                                                            display: true,
                                                            position: 'top',
                                                            labels: {
                                                                font: {
                                                                    weight: 'bold'
                                                                }
                                                            }
                                                        },
                                                        datalabels: {
                                                            color: '#000',
                                                            font: {
                                                                weight: 'bold',
                                                                size: 12
                                                            },
                                                            formatter: formatCurrency,
                                                            anchor: 'end',
                                                            align: 'top',
                                                            offset: 5,
                                                            clamp: true
                                                        },
                                                        tooltip: {
                                                            callbacks: {
                                                                label: function(context) {
                                                                    return `Total: ${formatCurrency(context.raw)}`;
                                                                }
                                                            },
                                                            bodyFont: {
                                                                weight: 'bold'
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
                                                                callback: formatCurrency,
                                                                font: {
                                                                    weight: 'bold'
                                                                }
                                                            }
                                                        },
                                                        x: {
                                                            grid: {
                                                                display: false
                                                            },
                                                            ticks: {
                                                                font: {
                                                                    weight: 'bold'
                                                                }
                                                            }
                                                        }
                                                    },
                                                    layout: {
                                                        padding: {
                                                            top: 20,
                                                            right: 20,
                                                            bottom: 20,
                                                            left: 20
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

                <!-- 1st data: Type of Funds - Total Budget -->
                <div class="doughnut-chart-container" style="display: none;">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="text-left mb-0"><strong>Type of Funds - Total Budget</strong></h4>
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
                                    foreach ($funds as $fund) {
                                    $labels[] = $fund->type_of_funds;
                                    $totalBudget += $fund->total_budget;
                                    $data[] = [
                                    'label' => $fund->type_of_funds,
                                    'data' => $fund->total_budget,
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
                                        <canvas id="typeOfFundsChart2"></canvas>
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
                                        #typeOfFundsChart2 {
                                            filter: none !important;
                                        }
                                    </style>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const ctx2 = document.getElementById('typeOfFundsChart2').getContext('2d');
                                            const typeOfFundsChart2 = new Chart(ctx2, {
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
                                                    cutoutpercentage: 70,
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


                <!-- 2nd data: Type of Funds - Breakdown -->
                <div class="row pt-4">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <!-- TABLE -->
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered" id="type-of-funds-table" style="width: 100%;">
                                                <thead style="border-top: 1px solid #ccc;">
                                                    <tr>
                                                        <th scope="col">Type of Funds</th>
                                                        <th scope="col">Project Count</th>
                                                        <th scope="col">Total Budget</th>
                                                        <th scope="col">Count %</th>
                                                        <th scope="col">Budget %</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($funds as $fund)
                                                    <tr>
                                                        <td>{{ $fund->type_of_funds }}</td>
                                                        <td class="text-center">{{ $fund->project_count }}</td>
                                                        <td class="text-center">
                                                            {{ number_format($fund->total_budget, 2, '.', ',') }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($fund->count_percentage, 2, '.', ',') . '%' }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ number_format($fund->budget_percentage, 2, '.', ',') . '%' }}
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <!-- <tr>
                                                        <td colspan="5" class="text-center">No project found.</td>
                                                    </tr> -->
                                                    @endforelse
                                                    <tr>
                                                        <td><strong>Total</strong></td>
                                                        <td class="text-center"><strong>{{ collect($funds)->sum('project_count') }}</strong></td>
                                                        <td class="text-center"><strong>{{ number_format(collect($funds)->sum('total_budget'), 2, '.', ',') }}</strong></td>
                                                        <td class="text-center"><strong>{{ number_format(collect($funds)->sum('count_percentage'), 2, '.', ',') . '%' }}</strong></td>
                                                        <td class="text-center"><strong>{{ number_format(collect($funds)->sum('budget_percentage'), 2, '.', ',') . '%' }}</strong></td>
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
