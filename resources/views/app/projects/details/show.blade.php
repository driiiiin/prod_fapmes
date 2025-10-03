<x-app-layout>
    <a href="{{ url()->previous() }}" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
        <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
    </a>
    <button id="print-project-btn" class="btn btn-primary" style="float: right; margin-right: 20px; margin-top: 10px;">
        <i class="fa fa-print" style="margin-right: 5px;"></i>Print This Project
    </button>
    <div class="container mt-5">
        <div id="printable-project-details">
            <div class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
                <div class="card-header" style="background-color: #296D98; color: white; padding: 10px;">
                    <h4 class="mb-0 text-center"><strong>Project Details</strong></h4>
                </div>
                <div class="card-body p-2">
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <p style="font-size: small;">{{ $project->project_id }}</p>
                        </div>
                        <div class="col-md-7">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <p style="font-size: small;">{{ $project->project_name }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="short_title" class="form-label" style="font-size: small;"><strong>Short Title <small>(ex. JICA, ADB, WB, WHO)</small>:</strong></label>
                            <p style="font-size: small;">{{ $project->short_title }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="funding_source" class="form-label" style="font-size: small;"><strong>Funding Source:</strong></label>
                            <p style="font-size: small;">{{ $project->funding_source }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="donor" class="form-label" style="font-size: small;"><strong>International Health Partners:</strong></label>
                            <p style="font-size: small;">{{ $project->donor }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="depdev" class="form-label" style="font-size: small;"><strong>DEP Dev:</strong></label>
                            <p style="font-size: small;">{{ $project->depdev }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="management" class="form-label" style="font-size: small;"><strong>Management:</strong></label>
                            <p style="font-size: small;">{{ $project->management }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="gph" class="form-label" style="font-size: small;"><strong>GPH Implemented:</strong></label>
                            <p style="font-size: small;">{{ $project->gph}}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="fund_type" class="form-label" style="font-size: small;"><strong>Fund Type:</strong></label>
                            <p style="font-size: small;">{{ $project->fund_type }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="fund_management" class="form-label" style="font-size: small;"><strong>Fund Management:</strong></label>
                            <p style="font-size: small;">{{ $project->fund_management }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="desk_officer" class="form-label" style="font-size: small;"><strong>Desk Officer:</strong></label>
                            <p style="font-size: small;">{{ $project->desk_officer }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="alignment" class="form-label" style="font-size: small;"><strong>Alignment with 8PAA:</strong></label>
                            <p style="font-size: small;">{{ $project->alignment }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="environmental" class="form-label" style="font-size: small;"><strong>Environmental and Social Risk:</strong></label>
                            <p style="font-size: small;">{{ $project->environmental }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="health_facility" class="form-label" style="font-size: small;"><strong>Health Facility:</strong></label>
                            <p style="font-size: small;">{{ $project->health_facility }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="development_objectives" class="form-label" style="font-size: small;"><strong>Development Objectives:</strong></label>
                            <p style="font-size: small;">{{ $project->development_objectives }}</p>
                        </div>


                        <div class="col-md-4">
                            <label for="sector" class="form-label" style="font-size: small;"><strong>Sector:</strong></label>
                            <p style="font-size: small;">{{ $project->sector }}</p>
                        </div>

                        <div class="col-md-2">
                            <label for="status" class="form-label" style="font-size: small;"><strong>Status:</strong></label>
                            @php
                                $status = strtolower($project->status);
                                $statusColor = match($status) {
                                    'pipeline' => 'secondary', // gray
                                    'active' => 'primary',     // blue
                                    'completed' => 'success',  // green
                                    default => 'dark'
                                };
                            @endphp
                            <p style="font-size: small;">
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-3">
                            <label for="sites" class="form-label" style="font-size: small;"><strong>Geographical Distribution:</strong></label>
                            <p style="font-size: small;">{{ $project->sites }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="agreement" class="form-label" style="font-size: small;"><strong>Agreement:</strong></label>
                            <p style="font-size: small;">
                                @if(!empty($project->agreement))
                                    <a href="{{ Storage::url($project->agreement) }}" target="_blank" rel="noopener noreferrer" style="text-decoration: underline; color: blue;">
                                        View Agreement
                                    </a>
                                @else
                                    <a href="#" id="no-agreement-link" style="text-decoration: underline; color: blue; cursor: pointer;">
                                        View Agreement
                                    </a>
                                @endif
                            </p>
                        </div>
                        </content>
                        </create_file>
                        <div class="col-md-3">
                            <label for="site_specific_reg" class="form-label" style="font-size: small;"><strong>Site Specific Region:</strong></label>
                            <p style="font-size: small;">{{ $project->site_specific_reg }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="site_specific_prov" class="form-label" style="font-size: small;"><strong>Site Specific Province:</strong></label>
                            <p style="font-size: small;">{{ $project->site_specific_prov }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="site_specific_city" class="form-label" style="font-size: small;"><strong>Site Specific City:</strong></label>
                            <p style="font-size: small;">{{ $project->site_specific_city }}</p>
                        </div>
                        <div class="col-md-3">
                            <label for="outcome" class="form-label" style="font-size: small;"><strong>Outcome:</strong></label>
                            <p style="font-size: small;">{{ $project->outcome }}</p>
                        </div>
                    </div>
                </div>

                <!-- Second DATA -->
                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold; border-bottom: 2px solid #296D98; color: #296D98">Implementation Schedule</h4>
                        </div>
                        <!-- <table class="table table-striped table-bordered" id="implementation-table" style="width: 100%;"> -->
                        <table class="table table-striped table-bordered" style="width: 100%;">
                            <thead style="border-top: 1px solid #ccc;">
                                <tr class="text-center">
                                    <!-- <th class="text-center">Action</th> -->
                                    <!-- <th class="text-center">ID</th> -->
                                    <th class="text-center">Project ID</th>
                                    <th class="text-center">Project Name</th>
                                    <th class="text-center">Start Date <br> <span style="font-size: 9px;">(MM-DD-YYYY)</span></th>
                                    <th class="text-center">End Date <br> <span style="font-size: 9px;">(MM-DD-YYYY)</span></th>
                                    <th class="text-center">Extension <br> <span style="font-size: 9px;">(MM-DD-YYYY)</span></th>
                                    <th class="text-center">Duration</th>
                                    <th class="text-center">Time Elapsed</th>
                                    <th class="text-center">% Time Elapsed</th>
                                    <th class="text-center">Date Encoded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($implementations->sortByDesc('created_at') as $implementation)
                                <tr class="implementation-row"
                                    data-project-id="{{ $implementation->project_id }}"
                                    data-project-name="{{ $implementation->project_name }}"
                                    data-start-date="{{ $implementation->start_date }}"
                                    data-end-date="{{ $implementation->end_date }}"
                                    data-extension="{{ $implementation->extension }}"
                                    data-duration="{{ $implementation->duration }}"
                                    data-time-elapsed="{{ $implementation->time_elapsed }}">
                                    <!-- <td class="text-center">{{ $implementation->id }}</td> -->
                                    <td class="text-center">{{ $implementation->project_id }}</td>
                                    <td class="text-center">{{ $implementation->project_name }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($implementation->start_date)->format('m-d-Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($implementation->end_date)->format('m-d-Y') }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($implementation->extension)->format('m-d-Y') }}</td>
                                    <td class="text-center">{{ number_format($implementation->getDurationAttribute(), 2) }}</td>
                                    <td class="text-center">{{ $implementation->getTimeElapsedAttribute() }}</td>
                                    <td class="text-center">{{ number_format($implementation->getPTimeElapsedAttribute(), 2) }}%</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($implementation->created_at)->format('m-d-Y h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Third DATA -->

                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold; border-bottom: 2px solid #296D98; color: #296D98;">Health Areas</h4>
                        </div>
                        <!-- <table class="table table-striped table-bordered" id="level-table" style="width: 100%;"> -->
                        <table class="table table-striped table-bordered" style="width: 100%;">
                            <thead style="border-top: 1px solid #ccc;">
                                <tr class="text-center">
                                    <!-- <th class="text-center">Action</th> -->
                                    <!-- <th class="text-center">ID</th> -->
                                    <th class="text-center">Project ID</th>
                                    <th class="text-center">Project Name</th>
                                    <th class="text-center">Health Area (Level 1)</th>
                                    <th class="text-center">Health Area (Level 2)</th>
                                    <th class="text-center">Health Systems Building Blocks</th>
                                    <th class="text-center">Level Budget(in Php)</th>
                                    <th class="text-center">Outcome</th>
                                    <th class="text-center">Date Encoded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($levels->sortByDesc('created_at') as $level)
                                <tr class="level-row"
                                    data-project-id="{{ $level->project_id }}"
                                    data-project-name="{{ $level->project_name }}"
                                    data-level1="{{ $level->level1 }}"
                                    data-level2="{{ $level->level2 }}"
                                    data-level3="{{ $level->level3 }}"
                                    data-l-budget="{{ $level->l_budget }}"
                                    data-outcome="{{ $level->outcome }}">
                                    <!-- <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2" style="align-items: center;">

                                    <a href="{{ route('projects.editThirdTab', $level->id) }}" title="Edit Level" role="button" aria-label="Edit Level">
                                        <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit">
                                    </a>
                                    <form action="{{ route('projects.destroyLevel', $level->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete Level" style="background: none; border: none; padding-top: 7px;">
                                            <img src="{{ asset('images/delete.png') }}" width="15" height="18" alt="Delete">
                                        </button>
                                    </form>
                                </div>
                                <script>
                                    document.querySelectorAll('.delete-button').forEach(button => {
                                        button.addEventListener('click', function(event) {
                                            event.preventDefault();
                                            const form = this.closest('.delete-form');
                                            Swal.fire({
                                                title: '<span style="font-size: medium;">Are you sure you want to delete this Levels permanently?</span>',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                cancelButtonText: '<span style="font-size: smaller;">Cancel</span>',
                                                confirmButtonText: '<span style="font-size: smaller;">Yes, delete it!</span>'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    form.submit();
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </td> -->
                                    <!-- <td class="text-center">{{ $level->id }}</td> -->
                                    <td class="text-center">{{ $level->project_id }}</td>
                                    <td class="text-center">{{ $level->project_name }}</td>
                                    <td class="text-center">{{ $level->level1 ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $level->level2 ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $level->level3 }}</td>
                                    <td class="text-center">{{ number_format($level->l_budget, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ $level->outcome }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($level->created_at)->format('m-d-Y h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Fourth DATA -->

                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold; border-bottom: 2px solid #296D98; color: #296D98">Financial Accomplishments</h4>
                        </div>
                        <!-- <table class="table table-striped table-bordered" id="financial-table" style="width: 100%;"> -->
                        <table class="table table-striped table-bordered" style="width: 100%;">
                            <thead style="border-top: 1px solid #ccc;">
                                <tr class="text-center">
                                    <!-- <th class="text-center">Action</th> -->
                                    <!-- <th class="text-center">ID</th> -->
                                    <th class="text-center">Project ID</th>
                                    <th class="text-center">Project Name</th>
                                    <th class="text-center">Amount in Original Currency</th>
                                    <th class="text-center">Currency</th>
                                    <th class="text-center">Exchange Rate</th>
                                    <th class="text-center">Total Project Cost (in Php)</th>
                                    <th class="text-center">Loan Proceeds (in Php)</th>
                                    <th class="text-center">Grant Proceeds (in Php)</th>
                                    <th class="text-center">GPH Counterpart (in Php)</th>
                                    <th class="text-center">Disbursement (in Php)</th>
                                    <th class="text-center">% Financial Accomplishment</th>
                                    <th class="text-center">Date Encoded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($financials->sortByDesc('created_at') as $financial)
                                <tr class="financial-row"
                                    data-project-id="{{ $financial->project_id }}"
                                    data-project-name="{{ $financial->project_name }}"
                                    data-orig-budget="{{ $financial->orig_budget }}"
                                    data-currency="{{ $financial->currency }}"
                                    data-rate="{{ $financial->rate }}"
                                    data-budget="{{ $financial->budget }}"
                                    data-lp="{{ $financial->lp }}"
                                    data-gp="{{ $financial->gp }}"
                                    data-gph-counterpart="{{ $financial->gph_counterpart }}"
                                    data-disbursement="{{ $financial->disbursement }}"
                                    data-p-disbursement="{{ $financial->p_disbursement }}">
                                    <!-- <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2" style="align-items: center;">

                                    <a href="{{ route('projects.editFourthTab', $financial->id) }}" title="Edit Financial" role="button" aria-label="Edit Financial">
                                        <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit">
                                    </a>
                                    <form action="{{ route('projects.destroyFinancial', $financial->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete Financial" style="background: none; border: none; padding-top: 7px;">
                                            <img src="{{ asset('images/delete.png') }}" width="15" height="18" alt="Delete">
                                        </button>
                                    </form>
                                </div>
                                <script>
                                    document.querySelectorAll('.delete-button').forEach(button => {
                                        button.addEventListener('click', function(event) {
                                            event.preventDefault();
                                            const form = this.closest('.delete-form');
                                            Swal.fire({
                                                title: '<span style="font-size: medium;">Are you sure you want to delete this Financial Accomplishment permanently?</span>',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#3085d6',
                                                cancelButtonColor: '#d33',
                                                cancelButtonText: '<span style="font-size: smaller;">Cancel</span>',
                                                confirmButtonText: '<span style="font-size: smaller;">Yes, delete it!</span>'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    form.submit();
                                                }
                                            });
                                        });
                                    });
                                </script>
                            </td> -->
                                    <!-- <td class="text-center">{{ $financial->id }}</td> -->
                                    <td class="text-center">{{ $financial->project_id }}</td>
                                    <td class="text-center">{{ $financial->project_name }}</td>
                                    <td class="text-center">{{ number_format($financial->orig_budget, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ $financial->currency }}</td>
                                    <td class="text-center">{{ number_format($financial->rate, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ number_format($financial->budget, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ number_format($financial->lp, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ number_format($financial->gp, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ number_format($financial->gph_counterpart, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ number_format($financial->disbursement, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ $financial->p_disbursement }}%</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($financial->created_at)->format('m-d-Y h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>


                <!-- Fifth DATA -->

                <div class="table-responsive">
                    <div class="table-wrapper">
                        <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold; border-bottom: 2px solid #296D98; color: #296D98">Physical Accomplishments</h4>
                        </div>
                        <!-- <table class="table table-striped table-bordered" id="physical-table" style="width: 100%;"> -->
                        <table class="table table-striped table-bordered" style="width: 100%;">
                            <thead style="border-top: 1px solid #ccc;">
                                <tr class="text-center">
                                    <!-- <th class="text-center">ID</th> -->
                                    <th class="text-center">Project ID</th>
                                    <th class="text-center">Project Name</th>
                                    <th class="text-center">Project Type</th>
                                    <th class="text-center">Year</th>
                                    <th class="text-center">Quarter</th>
                                    <th class="text-center">Overall Accomplishment (%)</th>
                                    <th class="text-center">Overall Target (%)</th>
                                    <th class="text-center">Slippage (%)</th>
                                    <th class="text-center">Remarks</th>
                                    <th class="text-center">Remaining % to be Accomplished <small style="font-size: xx-small;">(End of Quarter)</small></th>
                                    <th class="text-center">Design Monitoring Framework (DMF)</th>
                                    <th class="text-center">Date Encoded</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($physicals->sortByDesc('created_at') as $physical)
                                <tr class="physical-row"
                                    data-project-id="{{ $physical->project_id }}"
                                    data-project-name="{{ $physical->project_name }}"
                                    data-project-type1="{{ $physical->project_type1 }}"
                                    data-project-weight="{{ $physical->weight }}"
                                    data-actual="{{ $physical->actual }}"
                                    data-target="{{ $physical->target }}"
                                    data-project-weight1="{{ $physical->weight1 }}"
                                    data-actual1="{{ $physical->actual1 }}"
                                    data-target1="{{ $physical->target1 }}"
                                    data-outcome-file="{{ $physical->outcome_file }}">
                                    <!-- <td class="text-center">{{ $physical->id }}</td> -->
                                    <td class="text-center">{{ $physical->project_id }}</td>
                                    <td class="text-center">{{ $physical->project_name }}</td>
                                    <td class="text-center">
                                        @if(isset($physical->quarter) && isset($physical->weight) && isset($physical->actual) && isset($physical->target) &&
                                            isset($physical->quarter1) && isset($physical->weight1) && isset($physical->actual1) && isset($physical->target1))
                                        Infrastructure / Non-Infrastructure
                                        @elseif(isset($physical->quarter) && isset($physical->weight) && isset($physical->actual) && isset($physical->target))
                                        Infrastructure
                                        @elseif(isset($physical->quarter1) && isset($physical->weight1) && isset($physical->actual1) && isset($physical->target1))
                                        Non-Infrastructure
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $physical->year ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $physical->quarter ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $physical->overall_accomplishment ?? '0' }} %</td>
                                    <td class="text-center">{{ $physical->overall_target ?? '0' }} %</td>
                                    <td class="text-center">{{ $physical->slippage ?? 'N/A' }} %</td>
                                    <td class="text-center" style="color: {{ $physical->remarks === 'AHEAD' ? '#17a2b8' : ($physical->remarks === 'ON-TIME' ? '#28a745' : ($physical->remarks === 'BEHIND' ? '#dc3545' : ($physical->remarks === 'FOR VERIFICATION YEAR' ? '#ffd700' : ($physical->remarks === 'FOR VERIFICATION TARGET OR ACTUAL' ? '#ff0000' : 'inherit')))) }};"><strong>{{ $physical->remarks ?? 'N/A' }}</strong></td>
                                    <td class="text-center">{{ $physical->slippage_end_of_quarter ?? 'N/A' }} %</td>
                                    <td class="text-center">
                                        @if($physical->outcome_file)
                                        <a href="{{ Storage::url($physical->outcome_file) }}" target="_blank" style="color: blue; text-decoration: underline;">View DMF File</a>
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($physical->created_at)->format('m-d-Y h:i A') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="13" class="text-center">No data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>




            </div>
        </div>
        <!-- End of printable-project-details -->

    </div>
</x-app-layout>

<style>
@media print {
    @page {
        margin: 0.2in;
    }
    body * {
        visibility: hidden !important;
    }
    #printable-project-details, #printable-project-details * {
        visibility: visible !important;
    }
    #printable-project-details {
        position: absolute !important;
        left: 0; top: 0; width: 100vw;
        background: white !important;
        z-index: 9999;
        box-shadow: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    #print-project-btn, .btn, .navbar, .footer, .sidebar, a[href*="back"] {
        display: none !important;
    }
    .shadow, .shadow-md, .shadow-lg {
        box-shadow: none !important;
    }
    .card, .table-responsive, .table-wrapper {
        page-break-inside: avoid;
    }
    body, .bg-white, .card-header, .table th, .table td {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const noAgreementLink = document.getElementById('no-agreement-link');
        if (noAgreementLink) {
            noAgreementLink.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'No Attachment',
                    text: 'There is no agreement file attached for this project.',
                    confirmButtonText: 'OK'
                });
            });
        }
        // Print button handler
        const printBtn = document.getElementById('print-project-btn');
        if (printBtn) {
            printBtn.addEventListener('click', function() {
                window.print();
            });
        }
    });
</script>
