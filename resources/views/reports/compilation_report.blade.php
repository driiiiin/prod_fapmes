<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compilation Report {{ $year ? '- ' . $year : '' }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.6;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .header p {
            color: #7f8c8d;
            margin: 5px 0;
        }
        .summary-section {
            margin-bottom: 30px;
        }
        .summary-section h2 {
            color: #34495e;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
        }
        .stat-card h3 {
            color: #495057;
            margin-bottom: 10px;
            font-size: 1.1em;
        }
        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            color: #2c3e50;
        }
        .stat-card .percentage {
            font-size: 1.2em;
            color: #27ae60;
            margin-top: 5px;
        }
        .projects-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .projects-table th,
        .projects-table td {
            border: 1px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .projects-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .projects-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-completed { color: #27ae60; font-weight: bold; }
        .status-active { color: #f39c12; font-weight: bold; }
        .status-pipeline { color: #e74c3c; font-weight: bold; }
        .financial-summary {
            background: #ecf0f1;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .financial-summary h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .financial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .financial-item {
            text-align: center;
            padding: 15px;
            background: white;
            border-radius: 5px;
            border: 1px solid #bdc3c7;
        }
        .financial-item .label {
            font-size: 0.9em;
            color: #7f8c8d;
            margin-bottom: 5px;
        }
        .financial-item .amount {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
        }
        @media print {
            body { margin: 0; }
            .header { page-break-after: avoid; }
            .summary-section { page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>FAPMES Compilation Report</h1>
        <p>Comprehensive Project Management and Accomplishment Summary</p>
        @if($year)
            <p>Year: {{ $year }}</p>
        @else
            <p>All Years</p>
        @endif
        <p>Generated on: {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>

    <div class="summary-section">
        <h2>Project Overview</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Projects</h3>
                <div class="number">{{ number_format($totalProjects) }}</div>
            </div>
            <div class="stat-card">
                <h3>Completed Projects</h3>
                <div class="number">{{ number_format($completedProjects) }}</div>
                <div class="percentage">
                    {{ $totalProjects > 0 ? number_format(($completedProjects / $totalProjects) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="stat-card">
                <h3>Active Projects</h3>
                <div class="number">{{ number_format($activeProjects) }}</div>
                <div class="percentage">
                    {{ $totalProjects > 0 ? number_format(($activeProjects / $totalProjects) * 100, 1) : 0 }}%
                </div>
            </div>
            <div class="stat-card">
                <h3>Pipeline Projects</h3>
                <div class="number">{{ number_format($pipelineProjects) }}</div>
                <div class="percentage">
                    {{ $totalProjects > 0 ? number_format(($pipelineProjects / $totalProjects) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </div>

    <div class="summary-section">
        <h2>Financial Summary</h2>
        <div class="financial-summary">
            <div class="financial-grid">
                <div class="financial-item">
                    <div class="label">Total Budget</div>
                    <div class="amount">₱{{ number_format($totalBudget, 2) }}</div>
                </div>
                <div class="financial-item">
                    <div class="label">Total Obligation</div>
                    <div class="amount">₱{{ number_format($totalObligation, 2) }}</div>
                </div>
                <div class="financial-item">
                    <div class="label">Total Disbursement</div>
                    <div class="amount">₱{{ number_format($totalDisbursement, 2) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="summary-section">
        <h2>Physical Accomplishment Summary</h2>
        <div class="financial-summary">
            <div class="financial-grid">
                <div class="financial-item">
                    <div class="label">Total Physical Target</div>
                    <div class="amount">{{ number_format($totalPhysicalTarget) }}</div>
                </div>
                <div class="financial-item">
                    <div class="label">Total Physical Accomplishment</div>
                    <div class="amount">{{ number_format($totalPhysicalAccomplishment) }}</div>
                </div>
                <div class="financial-item">
                    <div class="label">Accomplishment Rate</div>
                    <div class="amount">
                        {{ $totalPhysicalTarget > 0 ? number_format(($totalPhysicalAccomplishment / $totalPhysicalTarget) * 100, 1) : 0 }}%
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($projects->count() > 0)
    <div class="summary-section">
        <h2>Project Details</h2>
        <table class="projects-table">
            <thead>
                <tr>
                    <th>Project ID</th>
                    <th>Short Title</th>
                    <th>Status</th>
                    <th>Completed Date</th>
                    <th>Budget Amount</th>
                    <th>Obligation Amount</th>
                    <th>Disbursement Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $project)
                <tr>
                    <td>{{ $project->project_id }}</td>
                    <td>{{ $project->short_title }}</td>
                    <td class="status-{{ strtolower($project->status) }}">{{ $project->status }}</td>
                    <td>{{ $project->completed_date ? \Carbon\Carbon::parse($project->completed_date)->format('M j, Y') : 'N/A' }}</td>
                    <td>₱{{ number_format($project->financialaccomplishment->sum('budget'), 2) }}</td>
                    <td>₱{{ number_format($project->financialaccomplishment->sum('disbursement'), 2) }}</td>
                    <td>₱{{ number_format($project->financialaccomplishment->sum('disbursement'), 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="summary-section">
        <h2>Report Summary</h2>
        <p>This compilation report provides a comprehensive overview of all projects in the FAPMES system.</p>
        <ul>
            <li><strong>Total Projects:</strong> {{ number_format($totalProjects) }} projects in the system</li>
            <li><strong>Completion Rate:</strong> {{ $totalProjects > 0 ? number_format(($completedProjects / $totalProjects) * 100, 1) : 0 }}% of projects are completed</li>
            <li><strong>Financial Performance:</strong> Total budget allocation of ₱{{ number_format($totalBudget, 2) }}</li>
            <li><strong>Physical Performance:</strong> {{ $totalPhysicalTarget > 0 ? number_format(($totalPhysicalAccomplishment / $totalPhysicalTarget) * 100, 1) : 0 }}% physical accomplishment rate</li>
        </ul>
    </div>
</body>
</html>
