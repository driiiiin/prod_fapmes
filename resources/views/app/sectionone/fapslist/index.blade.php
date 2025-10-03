<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <style>
        .main-container {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .table-container {
            flex: 1 1 70%;
            min-width: 300px;
            transition: all 0.3s ease;
        }
        .filter-section {
            flex: 1 0 25%;
            max-width: 300px;
            display: none;
            transition: all 0.3s ease;
        }
        /* Responsive for small screens */
        @media (max-width: 768px) {
            .filter-section {
                display: none;
                order: -1;
                width: 100%;
            }
            .filter-section.active {
                display: block;
            }
            .table-container {
                width: 100%;
            }
        }
        .toggle-filter-btn {
            color: black;
            border: none;
            padding: 6px 10px;
            font-size: 14px;
            cursor: pointer;
        }
    </style>

    <div class="container-fluid mt-4 pt-4" style="width: 90%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center position-relative">
                <h4 class="text-left mb-0"><strong>Foreign Assisted Projects (FAPs) Overview List</strong></h4>
                <!-- Tabs -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="project-list-tab" data-bs-toggle="tab" href="#project-list" role="tab"
                            aria-controls="project-list" aria-selected="true">Project List</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="progress-update-tab" data-bs-toggle="tab" href="#progress-update" role="tab"
                            aria-controls="progress-update" aria-selected="false">Progress Update</a>
                    </li>
                </ul>
                <button id="toggleFilterBtn" class="toggle-filter-btn ms-3">⚙️ Filter</button>
            </div>

            <div class="tab-content" id="myTabContent">
                <!-- Project List Tab -->
                <div class="tab-pane fade show active" id="project-list" role="tabpanel"
                    aria-labelledby="project-list-tab">
                    <div class="card-body pt-0">
                        <div class="main-container">
                            <div class="table-container">
                                <div style="margin-top: 10px; margin-left: 20px;">
                                    <button class="btn btn-sm btn-secondary" onclick="exportTableToCSV('fapslistTable1')"><span>CSV</span></button>
                                    <button class="btn btn-sm btn-secondary" onclick="exportTableToExcel('fapslistTable1')"><span>Excel</span></button>
                                    <button class="btn btn-sm btn-secondary" onclick="printTable('fapslistTable1')"><span>Print</span></button>
                                </div>
                                <div class="table-responsive">
                                    <table id="fapslistTable1" class="table table-striped">
                                        <!-- Table headers unchanged -->
                                        <thead>
                                            <tr>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">No.</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Project ID</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Project Name</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Funding Source</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Start Date</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">End Date</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Time Elapsed</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Disbursed (Php)</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Total Project Cost (Php)</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Cost in Original Currency</th>
                                                <th class="text-center hidden-column">Project Donor</th>
                                                <th class="text-center hidden-column">Type of Management</th>
                                                <th class="text-center hidden-column">DePDev</th>
                                                <th class="text-center hidden-column">Level 1</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projects->sortByDesc('created_at') as $project)
                                            <tr>
                                                <td class="text-center">{{ $project->id }}</td>
                                                <td class="text-center" style="min-width: 120px;">{{ $project->project_id }}</td>
                                                <td class="text-center" style="min-width: 180px;">{{ $project->project_name }}</td>
                                                <td class="text-center">{{ $project->funding_source }}</td>
                                                <td class="text-center" style="min-width: 100px;">
                                                    @php
                                                        $project_id = $project->project_id;
                                                        $project_start_date = \App\Models\ImplementationSchedule::where('project_id', $project_id)->orderByDesc('id')->value('start_date');
                                                    @endphp
                                                    {{ $project_start_date }}
                                                </td>
                                                <td class="text-center" style="min-width: 100px;">
                                                    @php
                                                        $latestSchedule = \App\Models\ImplementationSchedule::where('project_id', $project_id)->orderByDesc('id')->first();
                                                        $project_end_date = null;
                                                        if ($latestSchedule) {
                                                            $project_end_date = !empty($latestSchedule->extension) ? $latestSchedule->extension : $latestSchedule->end_date;
                                                        }
                                                    @endphp
                                                    {{ $project_end_date }}
                                                </td>
                                                <td class="text-center" style="min-width: 120px;">
                                                    <div>&nbsp;&nbsp;
                                                        @php
                                                            $latest_implementation = \App\Models\ImplementationSchedule::where('project_id', $project_id)->orderByDesc('id')->first();
                                                            $time_elapsed = $latest_implementation ? $latest_implementation->time_elapsed : 0;
                                                        @endphp
                                                        {{ $time_elapsed }} months
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $disbursement = \App\Models\FinancialAccomplishment::where('project_id', $project->project_id)->orderBy('id', 'desc')->value('disbursement');
                                                    @endphp
                                                    @if(!is_null($disbursement))
                                                        {{ number_format($disbursement, 2, '.', ',') }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $budget = \App\Models\FinancialAccomplishment::where('project_id', $project->project_id)->orderBy('id', 'desc')->value('budget');
                                                    @endphp
                                                    @if(!is_null($budget))
                                                        {{ number_format($budget, 2, '.', ',') }}
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $project_orig_budget = \App\Models\FinancialAccomplishment::where('project_id', $project->project_id)->value('orig_budget');
                                                        $project_currency = \App\Models\FinancialAccomplishment::where('project_id', $project->project_id)->value('currency');
                                                    @endphp
                                                    {{ number_format($project_orig_budget, 2, '.', ',') }} {{ $project_currency ? $project_currency : '' }}
                                                </td>
                                                <td class="text-center hidden-column">{{ $project->donor }}</td>
                                                <td class="text-center hidden-column">{{ $project->management }}</td>
                                                <td class="text-center hidden-column">{{ $project->depdev }}</td>
                                                <td class="text-center hidden-column">{{ $project->level_1 }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Filter Section -->
                            <div class="filter-section" id="filterSection1" style="margin-top: 50px;">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Filter</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="donors1" class="form-label" style="font-size: small;"><strong>Funding Source:</strong></label>
                                                <select class="form-select border" id="donors1" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach (DB::table('ref_funds')->select('funds_code')->get() as $donor)
                                                    <option value="{{ $donor->funds_code }}">{{ $donor->funds_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="management1" class="form-label" style="font-size: small;"><strong>Type of Management:</strong></label>
                                                <select class="form-select border" id="management1" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach (DB::table('ref_management')->select('management_desc')->get() as $management)
                                                    <option value="{{ $management->management_desc }}">{{ $management->management_desc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="depdev1" class="form-label" style="font-size: small;"><strong>DePDev:</strong></label>
                                                <select class="form-select border" id="depdev1" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach (DB::table('ref_depdev')->select('depdev_desc')->get() as $depdev)
                                                    <option value="{{ $depdev->depdev_desc }}">{{ $depdev->depdev_desc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Progress Update Tab -->
                <div class="tab-pane fade" id="progress-update" role="tabpanel" aria-labelledby="progress-update-tab">
                    <div class="card-body pt-0">
                        <div class="main-container">
                            <div class="table-container">
                                <div style="margin-top: 10px; margin-left: 20px;">
                                    <button class="btn btn-sm btn-secondary" onclick="exportTableToCSV('fapslistTable2')"><span>CSV</span></button>
                                    <button class="btn btn-sm btn-secondary" onclick="exportTableToExcel('fapslistTable2')"><span>Excel</span></button>
                                    <button class="btn btn-sm btn-secondary" onclick="printTable('fapslistTable2')"><span>Print</span></button>
                                </div>
                                <div class="table-responsive">
                                    <table id="fapslistTable2" class="table table-striped">
                                        <!-- Table content unchanged -->
                                        <thead>
                                            <tr>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">No.</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Project ID</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Project Title</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">IHP</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Time Elapsed</th>
                                                <th style="background-color: #296D98; color: white; line-height: 1;" class="text-center">Disbursed / Total Project Cost (Php)</th>
                                                <th class="text-center hidden-column">Donors</th>
                                                <th class="text-center hidden-column">Type of Management</th>
                                                <th class="text-center hidden-column">depdev</th>
                                                <th class="text-center hidden-column">Level 1</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projects->sortByDesc('created_at') as $project)
                                            <tr>
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td class="text-center" style="min-width: 150px;">{{ $project->project_id }}</td>
                                                <td class="text-center" style="min-width: 300px;">{{ $project->project_name }}</td>
                                                <td class="text-center" style="min-width: 100px;">{{ $project->donor }}</td>
                                                <td style="min-width: 250px;" class="text-center">
                                                    <div style="display: flex; justify-content: space-between;">
                                                        <div style="background-color: {{ $project->p_time_elapsed >= 100 ? '#eb4039' : '#4395eb' }}; border-radius: 10px; padding: 2px 5px; color: white;">
                                                            @php
                                                                $project_id = $project->project_id;
                                                                $implementation = \App\Models\ImplementationSchedule::where('project_id', $project_id)->orderByDesc('id')->first();
                                                                $project_start_date = $implementation ? $implementation->start_date : null;
                                                                $project_end_date = $implementation ? $implementation->end_date : null;
                                                                $extension = $implementation ? $implementation->extension : null;
                                                            @endphp
                                                            {{ $project_start_date ? date('Y', strtotime($project_start_date)) : '' }} &nbsp;-&nbsp;
                                                            @if($extension)
                                                                {{ date('Y', strtotime($extension)) }}
                                                            @else
                                                                {{ $project_end_date ? date('Y', strtotime($project_end_date)) : '' }}
                                                            @endif
                                                        </div>
                                                        <div>&nbsp;&nbsp;
                                                            @php
                                                                $latest_implementation = \App\Models\ImplementationSchedule::where('project_id', $project_id)
                                                                    ->orderByDesc('id')
                                                                    ->first();
                                                                $time_elapsed = $latest_implementation ? $latest_implementation->time_elapsed : 0;
                                                                $p_time_elapsed = $latest_implementation ? $latest_implementation->p_time_elapsed : 0;
                                                            @endphp
                                                            {{ $time_elapsed }} months ({{ number_format($p_time_elapsed, 2) }}%)&nbsp;&nbsp;&nbsp;&nbsp;
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center" style="min-width: 200px;">
                                                    <div class="progress-container">
                                                        @php
                                                            $project_disbursement = \App\Models\FinancialAccomplishment::where('project_id', $project->project_id)->value('disbursement');
                                                            $project_budget = \App\Models\FinancialAccomplishment::where('project_id', $project->project_id)->value('budget');
                                                        @endphp
                                                        <div class="progress-bar completed" style="width: {{ $project->p_disbursement }}%;">
                                                            {{ number_format($project_disbursement, 2) }} / {{ number_format($project_budget, 2) }}
                                                            <span class="progress-text">({{ number_format($project->p_disbursement, 2) }}%)</span>
                                                        </div>
                                                        <div class="progress-bar remaining" style="width: {{ 100 - $project->p_disbursement }}%;"></div>
                                                    </div>
                                                </td>
                                                <td class="text-center hidden-column">{{ $project->donor }}</td>
                                                <td class="text-center hidden-column">{{ $project->management }}</td>
                                                <td class="text-center hidden-column">{{ $project->depdev }}</td>
                                                <td class="text-center hidden-column">{{ $project->level_1 }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Filter Section -->
                            <div class="filter-section" id="filterSection2" style="margin-top: 50px;">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title">Filter</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="donors2" class="form-label" style="font-size: small;"><strong>Funding Source:</strong></label>
                                                <select class="form-select border" id="donors2" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach (DB::table('ref_funds')->select('funds_code')->get() as $donor)
                                                    <option value="{{ $donor->funds_code }}">{{ $donor->funds_code }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="management2" class="form-label" style="font-size: small;"><strong>Type of Management:</strong></label>
                                                <select class="form-select border" id="management2" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach (DB::table('ref_management')->select('management_desc')->get() as $management)
                                                    <option value="{{ $management->management_desc }}">{{ $management->management_desc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-12">
                                                <label for="depdev2" class="form-label" style="font-size: small;"><strong>depdev:</strong></label>
                                                <select class="form-select border" id="depdev2" style="font-size: small;">
                                                    <option value="All">All</option>
                                                    @foreach (DB::table('ref_depdev')->select('depdev_desc')->get() as $depdev)
                                                    <option value="{{ $depdev->depdev_desc }}">{{ $depdev->depdev_desc }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Functions -->
    <script>
        function exportTableToCSV(tableId) {
            var csv = [];
            var rows = document.querySelectorAll('#' + tableId + ' tr');
            rows.forEach(row => {
                var rowData = [];
                row.querySelectorAll('td, th').forEach(col => {
                    rowData.push('"' + col.innerText.replace(/"/g, '""') + '"');
                });
                csv.push(rowData.join(','));
            });
            var csvString = csv.join('\n');
            var a = document.createElement('a');
            a.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csvString);
            a.download = 'fapslist.csv';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        function exportTableToExcel(tableId) {
            var table = document.getElementById(tableId);
            var html = table.outerHTML;
            var blob = new Blob([html], { type: 'application/vnd.ms-excel' });
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = 'fapslist.xls';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function printTable(tableId) {
            var printWindow = window.open("", "_blank");
            printWindow.document.write('<html><head><title>Print Table</title></head><body>');
            printWindow.document.write(document.getElementById(tableId).outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
        }
    </script>

    <!-- DataTables & Toggle Script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

    <script>
        $(document).ready(function () {
            const $filters = $('.filter-section');
            $('#toggleFilterBtn').on('click', function () {
                const isActive = $filters.is(':visible');
                $filters.toggle();
                $('.main-container').toggleClass('full-width', !isActive);
            });

            // Initialize DataTables
            const fapslistTable1 = $('#fapslistTable1').DataTable({
                responsive: true,
                columnDefs: [{ targets: [10, 11, 12, 13], visible: false }],
                pageLength: -1,
                lengthMenu: [[-1], ["All"]]
            });

            const fapslistTable2 = $('#fapslistTable2').DataTable({
                responsive: true,
                columnDefs: [{ targets: [6, 7, 8, 9], visible: false }]
            });

            // Apply filters using generalized function
            function applyFilters(table, donorSel, managementSel, depdevSel, donorCol, managementCol, depdevCol) {
                const donor = $(donorSel).val();
                const management = $(managementSel).val();
                const depdev = $(depdevSel).val();

                table.columns([donorCol, managementCol, depdevCol]).search('').draw();

                if (donor && donor !== 'All') table.column(donorCol).search(donor);
                if (management && management !== 'All') table.column(managementCol).search(management);
                if (depdev && depdev !== 'All') table.column(depdevCol).search(depdev);

                table.draw();
            }

            // Event listeners for filters
            $('#donors1, #management1, #depdev1').on('change', function () {
                applyFilters(fapslistTable1, '#donors1', '#management1', '#depdev1',  10, 11, 12);
            });

            $('#donors2, #management2, #depdev2').on('change', function () {
                applyFilters(fapslistTable2, '#donors2', '#management2', '#depdev2', 6, 7, 8);
            });

            // Optional: Redraw table when switching tabs
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                const target = $(e.target).attr("href");
                if (target === "#progress-update") {
                    fapslistTable2.columns.adjust().draw();
                } else if (target === "#project-list") {
                    fapslistTable1.columns.adjust().draw();
                }
            });
        });
    </script>
@endif
</x-app-layout>
