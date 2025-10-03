<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAPMES Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 10px;
            background-color: #fff;
        }

        .report-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }

        .report-header h1 {
            color: #007bff;
            margin-bottom: 8px;
            font-size: 22px;
        }

        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .section.bordered {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .section h2 {
            color: #007bff;
            margin-bottom: 12px;
            font-size: 16px;
            border-bottom: 1px solid #007bff;
            padding-bottom: 4px;
        }

        .section h3 {
            color: #495057;
            margin-bottom: 8px;
            font-size: 14px;
            margin-top: 15px;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 12px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #ddd;
            padding: 6px 10px;
            text-align: left;
        }

        .summary-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }

        .summary-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .summary-table tr.table-info {
            background-color: #d1ecf1;
            font-weight: bold;
        }

        .report-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            color: #666;
            font-style: italic;
            font-size: 11px;
        }

        .chart-section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .chart-section h3 {
            color: #495057;
            margin-bottom: 12px;
            font-size: 14px;
            text-align: center;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 15px 0;
            page-break-inside: avoid;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }

        @media print {
            body {
                padding: 5px;
                font-size: 11px;
            }

            .report-header {
                margin-bottom: 15px;
            }

            .report-header h1 {
                font-size: 20px;
            }

            .section {
                margin-bottom: 15px;
                page-break-inside: avoid;
            }

            .section.bordered {
                padding: 10px;
            }

            .section h2 {
                font-size: 14px;
                margin-bottom: 8px;
            }

            .section h3 {
                font-size: 12px;
                margin-bottom: 6px;
            }

            .summary-table {
                font-size: 10px;
                margin-bottom: 8px;
            }

            .summary-table th,
            .summary-table td {
                padding: 4px 6px;
            }

            .chart-section {
                margin-bottom: 20px;
                page-break-inside: avoid;
            }

            .chart-container {
                margin: 10px 0;
                page-break-inside: avoid;
            }

            .report-footer {
                margin-top: 20px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
    {{ $slot }}
</body>
</html>
