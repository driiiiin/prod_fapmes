@php
    $title = 'Report';
    if ($type === 'complete') $title = 'Monthly Report';
    elseif ($type === 'section1') $title = 'Quarterly Report';
    elseif ($type === 'section2') $title = 'Yearly Report';
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}{{ $year ? ' for ' . $year : '' }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        h2 { margin-top: 2em; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 2em; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>{{ $title }}{{ $year ? ' for ' . $year : '' }}</h1>
    @if(count($grouped))
        @foreach($grouped as $group => $projects)
            <h2>{{ $group }}</h2>
            <table>
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Completed Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->project_name ?? $project->short_title }}</td>
                        <td>{{ $project->completed_date }}</td>
                        <td>{{ $project->status }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    @else
        <p>No projects found for this report.</p>
    @endif
</body>
</html>
