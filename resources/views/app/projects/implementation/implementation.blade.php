<div class="tab-pane fade" id="implementation" role="tabpanel" aria-labelledby="implementation-tab">
    <div class="container">
        <div id="input-fields" class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <form action="{{ route('projects.storeSecondTab') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #296D98; color: white; padding: 10px;">
                    <div class="flex-grow-1 text-center">
                        <h4 class="mb-0"><strong>Implementation Schedule</strong></h4>
                    </div>
                    <div class="text-end">
                        <button type="reset" class="btn btn-secondary btn-sm" title="Clear Inputs">
                            <i class="fa fa-eraser" aria-hidden="true"></i> Clear
                        </button>
                    </div>
                </div>
                <div class="card-body p-2">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" required style="font-size: small;" pattern="\w{2}-\w{2}-\w{2}-\w{2}" title="Format: XX-XX-XX-XX" readonly>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project ID in the format XX-XX-XX-XX.
                            </div>
                        </div>
                        <script>
                            const input = document.getElementById('project_id');

                            input.addEventListener('input', function() {
                                // Remove all non-digit characters
                                let value = this.value.replace(/\D/g, '');

                                // Format the value as 00-00-00-00
                                if (value.length > 8) {
                                    value = value.slice(0, 8); // Limit to 8 digits
                                }
                                const formattedValue = value.replace(/(\d{2})(?=\d)/g, '$1-').slice(0, 14); // Add hyphens

                                this.value = formattedValue;
                            });
                        </script>
                        <div class="col-md-9">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <input type="text" class="form-control border" id="project_name" name="project_name" required style="font-size: small;" readonly value="{{ old('project_name', $project_name ?? '') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project name.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="start_date" class="form-label" style="font-size: small;">
                                <strong>Start Date: <span style="color: red;">*</span></strong>
                            </label>
                            <input type="date"
                                class="form-control border @error('start_date') is-invalid @enderror"
                                id="start_date"
                                name="start_date"
                                required
                                style="font-size: small;"
                                value="{{ old('start_date') }}">
                            @error('start_date')
                            <input type="hidden" id="error-message" value="{{ $message }}">
                            @enderror
                        </div>

                        <!-- <div class="col-md-3">
                            <label for="interim_date" class="form-label" style="font-size: small;">
                                <strong>Interim Date:</strong>
                            </label>
                            <input type="date"
                                class="form-control border @error('interim_date') is-invalid @enderror"
                                id="interim_date"
                                name="interim_date"
                                style="font-size: small;">
                            @error('interim_date')
                            <input type="hidden" id="error-message" value="{{ $message }}">
                            @enderror
                        </div> -->

                        <div class="col-md-3">
                            <label for="end_date" class="form-label" style="font-size: small;">
                                <strong>End Date: <span style="color: red;">*</span></strong>
                            </label>
                            <input type="date"
                                class="form-control border @error('end_date') is-invalid @enderror"
                                id="end_date"
                                name="end_date"
                                required
                                style="font-size: small;"
                                value="{{ old('end_date') }}">
                            @error('end_date')
                            <input type="hidden" id="error-message" value="{{ $message }}">
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="extension" class="form-label" style="font-size: small;">
                                <strong>Extension Date:</strong>
                            </label>
                            <input type="date"
                                class="form-control border @error('extension') is-invalid @enderror"
                                id="extension"
                                name="extension"
                                style="font-size: small;">
                            @error('extension')
                            <input type="hidden" id="error-message" value="{{ $message }}">
                            @enderror
                        </div>

                        <!-- <div class="col-md-3">
                            <label for="duration" class="form-label" style="font-size: small;">
                                <strong>Duration: <span style="color: red;">*</span></strong>
                            </label>
                            <input type="number"
                                class="form-control border @error('duration') is-invalid @enderror"
                                id="duration"
                                name="duration"
                                required
                                style="font-size: small;">
                            @error('duration')
                            <input type="hidden" id="error-message" value="{{ $message }}">
                            @enderror
                        </div>

                        <div class="col-md-3">
                            <label for="time_elapsed" class="form-label" style="font-size: small;">
                                <strong>Time Elapsed: <span style="color: red;">*</span></strong>
                            </label>
                            <input type="number"
                                class="form-control border @error('time_elapsed') is-invalid @enderror"
                                id="time_elapsed"
                                name="time_elapsed"
                                required
                                style="font-size: small;">
                            @error('time_elapsed')
                            <input type="hidden" id="error-message" value="{{ $message }}">
                            @enderror
                        </div> -->

                        <script>
                            // Check if there are any error messages and display them
                            const errorMessage = document.getElementById('error-message');
                            if (errorMessage) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: errorMessage.value,
                                });
                            }
                        </script>

                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-success btn-sm" name="add" title="Add New Schedule">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Schedule
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="col-md-12 text-center mt-3 mb-2">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">Implementation Schedule</h4>
                </div>
                <!-- <table class="table table-striped table-bordered" id="implementation-table" style="width: 100%;"> uncomment this to show search bar -->
                <table class="table table-striped table-bordered" id="implementation-table" style="width: 100%;">
                    <thead style="border-top: 1px solid #ccc;">
                        <tr class="text-center">
                            <th class="text-center">Action</th>
                            <!-- <th class="text-center">ID</th> -->
                            <th class="text-center">Project ID</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Start Date <br> <span style="font-size: 9px;">(MM-DD-YYYY)</span></th>
                            <th class="text-center">End Date <br> <span style="font-size: 9px;">(MM-DD-YYYY)</span></th>
                            <th class="text-center">Extension Date<br> <span style="font-size: 9px;">(MM-DD-YYYY)</span></th>
                            <th class="text-center">Duration <br> <span style="font-size: 9px;">(in months)</span></th>
                            <th class="text-center">Time Elapsed <br> <span style="font-size: 9px;">(in months)</span></th>
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
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2" style="align-items: center;">


                                    @if(Auth::user()->userlevel == -1 || Auth::user()->userlevel == 2 || Auth::user()->userlevel == 5)
                                        <a href="{{ route('projects.editSecondTab', $implementation->id) }}" title="Edit Implementation Schedule" role="button" aria-label="Edit Implementation Schedule" style="margin-top: 3px;">
                                            <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit">
                                        </a>
                                    @endif
                                    @if(Auth::user()->userlevel == -1)
                                    <form action="{{ route('projects.destroyImplementationSchedule', $implementation->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete Implementation Schedule" style="background: none; border: none; padding-top: 7px; margin-top: 3px;">
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
                                                title: '<span style="font-size: medium;">Are you sure you want to delete this implementation schedule permanently?</span>',
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
                                @endif
                            </td>
                            <!-- <td class="text-center">{{ $implementation->id }}</td> -->
                            <td class="text-center">{{ $implementation->project_id }}</td>
                            <td class="text-center">{{ $implementation->project_name }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($implementation->start_date)->format('m-d-Y') }}</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($implementation->end_date)->format('m-d-Y') }}</td>
                            <td class="text-center">
                                {{ $implementation->extension ? \Carbon\Carbon::parse($implementation->extension)->format('m-d-Y') : 'N/A' }}
                            </td>
                            <td class="text-center">{{ $implementation->getDurationAttribute() }}</td>
                            <td class="text-center">{{ $implementation->getTimeElapsedAttribute() }}</td>
                            <td class="text-center">{{ number_format($implementation->getPTimeElapsedAttribute(), 2) }}%</td>
                            <td class="text-center">{{ \Carbon\Carbon::parse($implementation->created_at)->format('m-d-Y h:i A') }}</td>
                        </tr>
                        @empty
                        <!-- <tr>
                            <td colspan="11" class="text-center">No data available</td>
                        </tr> -->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


    </div>
</div>
