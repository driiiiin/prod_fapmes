<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header text-start">
                        <h2 class="card-title text-primary" style="font-size: 1.2rem; font-weight: bold;">Reports</h2>
                        <p class="card-text">Select your report type.</p>
                    </div>
                    <div class="card-body">
                        <form id="reportForm">
                            <!-- Report Type Selection -->
                            <div class="mb-3">
                                <label for="report-type" class="form-label font-weight-bold">Report Type</label>
                                <select id="report-type" class="form-select" required>
                                    <option value="" disabled {{ request('report-type') ? '' : 'selected' }}>Select a report type...</option>
                                    <option value="complete" {{ request('report-type') == 'complete' ? 'selected' : '' }}>Monthly Report</option>
                                    <option value="section1" {{ request('report-type') == 'section1' ? 'selected' : '' }}>Quarterly Report</option>
                                    <option value="section2" {{ request('report-type') == 'section2' ? 'selected' : '' }}>Yearly Report</option>
                                    <!-- <option value="section4" {{ request('report-type') == 'section4' ? 'selected' : '' }}>Dashboard Summary Report</option> -->
                                    <!-- <option value="section5" {{ request('report-type') == 'section5' ? 'selected' : '' }}>Overall Budget Distribution Report</option> -->
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="report-year" class="form-label font-weight-bold">Year</label>
                                <input type="text" id="report-year" name="report-year" class="form-control" placeholder="Enter year (e.g. 2024 or leave blank for all years)" value="{{ request('report-year') }}">
                            </div>
                            <div class="mb-3">
                                <label for="report-month" class="form-label font-weight-bold">Month</label>
                                <select id="report-month" name="report-month" class="form-select">
                                    <option value="" {{ request('report-month') == '' ? 'selected' : '' }}>All Months</option>
                                    <option value="1" {{ request('report-month') == '1' ? 'selected' : '' }}>January</option>
                                    <option value="2" {{ request('report-month') == '2' ? 'selected' : '' }}>February</option>
                                    <option value="3" {{ request('report-month') == '3' ? 'selected' : '' }}>March</option>
                                    <option value="4" {{ request('report-month') == '4' ? 'selected' : '' }}>April</option>
                                    <option value="5" {{ request('report-month') == '5' ? 'selected' : '' }}>May</option>
                                    <option value="6" {{ request('report-month') == '6' ? 'selected' : '' }}>June</option>
                                    <option value="7" {{ request('report-month') == '7' ? 'selected' : '' }}>July</option>
                                    <option value="8" {{ request('report-month') == '8' ? 'selected' : '' }}>August</option>
                                    <option value="9" {{ request('report-month') == '9' ? 'selected' : '' }}>September</option>
                                    <option value="10" {{ request('report-month') == '10' ? 'selected' : '' }}>October</option>
                                    <option value="11" {{ request('report-month') == '11' ? 'selected' : '' }}>November</option>
                                    <option value="12" {{ request('report-month') == '12' ? 'selected' : '' }}>December</option>
                                </select>
                            </div>

                            <!-- Output Format Selection -->
                            <div class="mb-3">
                                <label class="form-label font-weight-bold">Output Format</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="outputFormat" id="htmlFormat" value="html" checked>
                                    <label class="form-check-label" for="htmlFormat">
                                        <i class="fa fa-eye text-info"></i> Preview (HTML)
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer d-flex flex-column align-items-stretch">
                        <button id="generateReport" class="btn btn-primary" onclick="handleGenerateReport()">Generate Report</button>
                    </div>
                </div>
            </div>

            <!-- Preview Area -->
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Report Preview:</h5>
                            <!-- <button class="btn btn-secondary" onclick="printDiv('previewContent')"><i class="fa fa-print" aria-hidden="true"></i> Print to PDF</button> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="previewContent" class="border p-3 bg-light" style="height: 400px; overflow-y: auto;">
                            <p>Select a report type and output format, then click "Generate Report" to see the preview here.</p>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-select options if parameters are provided (for direct links from dashboard)
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const reportType = urlParams.get('report-type');
            const reportYear = urlParams.get('report-year');

            if (reportType && reportYear !== null) {
                // Auto-select the options
                document.getElementById('report-type').value = reportType;
                document.getElementById('report-year').value = reportYear;

                // Show message in preview area
                const previewContent = document.getElementById('previewContent');
                previewContent.innerHTML = `
                    <div class="alert alert-info">
                        Click "Generate Report" to create your report.
                    </div>
                `;
            }
        });

        function handleGenerateReport() {
            const reportType = document.getElementById('report-type').value;
            const reportYear = document.getElementById('report-year').value;
            const outputFormat = document.querySelector('input[name="outputFormat"]:checked')?.value;
            const previewContent = document.getElementById('previewContent');

            if (!reportType || !outputFormat) {
                alert("Please select report type and output format.");
                return;
            }

            if (reportYear === null || reportYear === undefined) {
                alert("Please select a year.");
                return;
            }

            // Show loading state
            previewContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Generating report...</p></div>';

            // Handle dashboard summary report specifically
            if (reportType === 'section4') {
                if (outputFormat === 'html') {
                    // For HTML preview, redirect to preview page
                    const previewUrl = `/preview-dashboard-summary-report?year=${reportYear}`;
                    window.open(previewUrl, '_blank');

                    // Show success message in preview
                    previewContent.innerHTML = `
                        <div class="alert alert-success">
                            <strong>Report Preview Generated Successfully!</strong><br>
                            The preview has been opened in a new tab. If it didn't open automatically,
                            <a href="${previewUrl}" target="_blank" class="alert-link">click here to view the preview</a>.
                        </div>
                    `;
                } else {
                    // For PDF, use existing functionality
                    generateDashboardSummaryReport(reportYear, outputFormat);
                }
                return;
            }

            // Make AJAX request to generate report
            fetch(`/generate-report?type=${reportType}&format=${outputFormat}&year=${reportYear}`)
                .then(response => {
                    if (response.ok) {
                        if (outputFormat === 'pdf') {
                            // Create a download link for PDF
                            const url = window.URL.createObjectURL(response.blob());
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = `${reportType}_report_${reportYear}.pdf`;
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                            a.remove();

                            // Show success message in preview
                            previewContent.innerHTML = '<div class="alert alert-success">PDF report has been generated and downloaded.</div>';
                        } else {
                            // Show HTML preview
                            response.text().then(data => {
                                previewContent.innerHTML = `<iframe srcdoc="${data}" style="width: 100%; height: 100%; border: none;"></iframe>`;
                            });
                        }
                    } else {
                        // Show error message in preview
                        response.text().then(data => {
                            previewContent.innerHTML = `<div class="alert alert-danger">${data}</div>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    previewContent.innerHTML = '<div class="alert alert-danger">Error generating report. Please try again.</div>';
                });
        }

        function generateDashboardSummaryReport(year, format) {
            const previewContent = document.getElementById('previewContent');

            // Show loading state
            previewContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Generating dashboard summary report...</p></div>';

            // Make AJAX request to generate dashboard summary report
            fetch(`/generate-dashboard-summary-report?year=${year}&format=${format}`)
                .then(response => {
                    if (response.ok) {
                        if (format === 'pdf') {
                            // For PDF, open in new window/tab
                            const url = `/generate-dashboard-summary-report?year=${year}&format=${format}`;
                            window.open(url, '_blank');

                            // Show success message in preview
                            previewContent.innerHTML = `
                                <div class="alert alert-success">
                                    <strong>PDF Report Generated Successfully!</strong><br>
                                    The PDF has been opened in a new tab. If it didn't open automatically,
                                    <a href="${url}" target="_blank" class="alert-link">click here to view the PDF</a>.
                                </div>
                            `;
                        } else {
                            // Show HTML preview
                            response.text().then(data => {
                                previewContent.innerHTML = `<iframe srcdoc="${data}" style="width: 100%; height: 100%; border: none;"></iframe>`;
                            });
                        }
                    } else {
                        // Show error message in preview
                        response.text().then(data => {
                            previewContent.innerHTML = `<div class="alert alert-danger">${data}</div>`;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    previewContent.innerHTML = '<div class="alert alert-danger">Error generating dashboard summary report. Please try again.</div>';
                });
        }

        function printDiv(elementId) {
            const element = document.getElementById(elementId);
            if (element) {
                const iframe = element.querySelector('iframe');
                if (iframe) {
                    // Get the iframe content
                    const iframeContent = iframe.srcdoc;

                    // Create a new window with the iframe content
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(iframeContent);
                    printWindow.document.close();

                    // Wait for content to load then print
                    printWindow.onload = function() {
                        printWindow.print();
                        printWindow.close();
                    };
                } else {
                    // If no iframe, print the element directly
                    const printWindow = window.open('', '_blank');
                    printWindow.document.write(element.innerHTML);
                    printWindow.document.close();
                    printWindow.onload = function() {
                        printWindow.print();
                        printWindow.close();
                    };
                }
            }
        }
    </script>
@endif
</x-app-layout>

