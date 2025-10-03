<div class="tab-pane fade" id="physical" role="tabpanel" aria-labelledby="physical-tab">
    <div class="container">
        <div id="input-fields" class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <form action="{{ route('projects.storeFifthTab') }}" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                @csrf
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #296D98; color: white; padding: 10px;">
                    <div class="flex-grow-1 text-center">
                        <h4 class="mb-0"><strong>Physical Accomplishments</strong></h4>
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
                            <input type="text" class="form-control border" id="project_id" name="project_id" required style="font-size: small;" pattern="\w{2}-\w{2}-\w{2}-\w{2}" title="Format: XX-XX-XX-XX" readonly value="{{ old('project_id', $project_id ?? '') }}">
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

                        <div class="col-md-2">
                            <label for="project_type" class="form-label" style="font-size: small;">
                                <strong>Project Type:</strong>
                            </label>
                            <input type="text" class="form-control border" id="project_type" name="project_type" value="Infrastructure" readonly style="font-size: small;">
                        </div>

                        <div class="col-md-2">
                            <label for="year" class="form-label" style="font-size: small;">
                                <strong>Year:</strong>
                            </label>
                            <input type="text"
                                class="form-control border @error('year') is-invalid @enderror"
                                id="year"
                                name="year"
                                pattern="\d{4}"
                                placeholder="YYYY"
                                style="font-size: small;"
                                value="{{ old('year') }}"
                                oninput="this.value = this.value.replace(/\D/g, ''); if(this.value.length > 4) this.value = this.value.slice(0,4); document.getElementById('year1').value = this.value;"
                                onkeypress="return this.value.length < 4">
                            @error('year')
                            <div class="invalid-feedback" style="font-size: small;">
                                Please enter exactly 4 digits
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="quarter" class="form-label" style="font-size: small;">
                                <strong>Quarter:</strong>
                            </label>
                            <select class="form-select border @error('quarter') is-invalid @enderror" id="quarter" name="quarter" style="font-size: small;">
                                <option value="">Select Quarter</option>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ old('quarter') == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
                                    @endfor
                            </select>
                            @error('quarter')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="weight" class="form-label" style="font-size: small;">
                                <strong>Weight (%):</strong>
                            </label>
                            <input type="number" step="0.01" class="form-control border @error('weight') is-invalid @enderror" id="weight" name="weight" style="font-size: small;">
                            @error('weight')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="actual" class="form-label" style="font-size: small;"><strong>Actual (%):</strong></label>
                            <input type="number"
                                class="form-control border @error('actual') is-invalid @enderror"
                                id="actual"
                                name="actual"
                                required
                                min="0"
                                max="100"
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('actual') }}">
                            @error('actual')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="target" class="form-label" style="font-size: small;"><strong>Target (%):</strong></label>
                            <input type="number"
                                class="form-control border @error('target') is-invalid @enderror"
                                id="target"
                                name="target"
                                required
                                min="0"
                                max="100"
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('target') }}">
                            @error('target')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        <hr style="border-top: 5px solid black; margin-top: 20px; margin-bottom: 20px;">



                        <div class="col-md-2">
                            <label for="project_type1" class="form-label" style="font-size: small;">
                                <strong>Project Type:</strong>
                            </label>
                            <input type="text" class="form-control border" id="project_type1" name="project_type1" value="Non-Infrastructure" readonly style="font-size: small;">
                        </div>

                        <div class="col-md-2">
                            <label for="year1" class="form-label" style="font-size: small;">
                                <strong>Year:</strong>
                            </label>
                            <input type="text"
                                class="form-control border @error('year1') is-invalid @enderror"
                                id="year1"
                                name="year1"
                                pattern="\d{4}"
                                placeholder="YYYY"
                                style="font-size: small;"
                                value="{{ old('year1') }}"
                                oninput="this.value = this.value.replace(/\D/g, ''); if(this.value.length > 4) this.value = this.value.slice(0,4); document.getElementById('year').value = this.value;"
                                onkeypress="return this.value.length < 4">
                            @error('year1')
                            <div class="invalid-feedback" style="font-size: small;">
                                Please enter exactly 4 digits
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="quarter1" class="form-label" style="font-size: small;">
                                <strong>Quarter:</strong>
                            </label>
                            <select class="form-select border @error('quarter1') is-invalid @enderror" id="quarter1" name="quarter1" style="font-size: small;">
                                <option value="">Select Quarter</option>
                                @for($i = 1; $i <= 4; $i++)
                                    <option value="{{ $i }}" {{ old('quarter1') == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
                                    @endfor
                            </select>
                            @error('quarter1')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <script>
                            // Get references to both quarter select elements
                            const quarter = document.getElementById('quarter');
                            const quarter1 = document.getElementById('quarter1');

                            // Add event listeners to both selects
                            quarter.addEventListener('change', function() {
                                quarter1.value = this.value;
                            });

                            quarter1.addEventListener('change', function() {
                                quarter.value = this.value;
                            });
                        </script>

                        <div class="col-md-2">
                            <label for="weight1" class="form-label" style="font-size: small;">
                                <strong>Weight (%):</strong>
                            </label>
                            <input type="number" step="0.01" class="form-control border @error('weight1') is-invalid @enderror" id="weight1" name="weight1" style="font-size: small;">
                            @error('weight1')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="actual1" class="form-label" style="font-size: small;"><strong>Actual (%):</strong></label>
                            <input type="number"
                                class="form-control border @error('actual1') is-invalid @enderror"
                                id="actual1"
                                name="actual1"
                                required
                                min="0"
                                max="100"
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('actual1') }}">
                            @error('actual1')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="target1" class="form-label" style="font-size: small;"><strong>Target (%):</strong></label>
                            <input type="number"
                                class="form-control border @error('target1') is-invalid @enderror"
                                id="target1"
                                name="target1"
                                required
                                min="0"
                                max="100"
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('target1') }}">
                            @error('target1')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <!-- <div class="col-md-2">
                            <label for="slippage" class="form-label" style="font-size: small;"><strong>Slippage:</strong></label>
                            <input type="text"
                                class="form-control border @error('slippage') is-invalid @enderror"
                                id="slippage"
                                name="slippage"
                                required
                                style="font-size: small;"
                                value="{{ old('slippage') }}">
                            @error('slippage')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                        <div class="col-md-2">
                            <label for="remarks" class="form-label" style="font-size: small;"><strong>Remarks:</strong></label>
                            <input type="text"
                                class="form-control border @error('remarks') is-invalid @enderror"
                                id="remarks"
                                name="remarks"
                                required
                                style="font-size: small;"
                                value="{{ old('remarks') }}">
                            @error('remarks')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div> -->

                        <div class="col-md-2">
                            <label for="outcome_file" class="form-label" style="font-size: small;">
                                <strong>Design Monitoring Framework (DMF):</strong>
                            </label>
                            <input type="file"
                                class="form-control border @error('outcome_file') is-invalid @enderror"
                                id="outcome_file"
                                name="outcome_file"
                                style="font-size: small;"
                                accept=".pdf"
                                max="25600">
                            <small style="font-size: x-small;">
                                <span style="color: #296D98;">Accepted file type: PDF (Max size: 25MB)</span>
                            </small>
                            @error('outcome_file')
                            <div class="invalid-feedback" style="font-size: small;">
                                Please upload an outcome file (PDF) up to 25MB.
                            </div>
                            @enderror
                        </div>

                        <script>
                            document.getElementById('outcome_file').addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                if (file && file.size > 25 * 1024 * 1024) { // 25MB in bytes
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'File Too Large',
                                        text: 'Your file is above 25MB. Please select a smaller file.',
                                    });
                                    event.target.value = '';
                                }
                            });
                        </script>

                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-success btn-sm" name="add" title="Add New Physical Accomplishment">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Add New Physical Accomplishment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="col-md-12 text-center mt-3 mb-2">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">Physical Accomplishments</h4>
                </div>
                <table class="table table-striped table-bordered" id="physical-table" style="width: 100%;">
                    <thead style="border-top: 1px solid #ccc;">
                        <tr class="text-center">
                            <th class="text-center">Action</th>
                            <!-- <th class="text-center">ID</th> -->
                            <th class="text-center">Project ID</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Project Type</th>
                            <th class="text-center">Year</th>
                            <th class="text-center">Quarter</th>
                            <th class="text-center">Overall Accomplishment (%)</th>
                            <th class="text-center">Overall Target (%)</th>

                            <!-- <th class="text-center">Quarter</th>
                            <th class="text-center">Weight</th>
                            <th class="text-center">Actual</th>
                            <th class="text-center">Target</th> -->
                            <!-- <th class="text-center">Target <small style="font-size: xx-small;">(End of Quarter)</small></th> -->
                            <th class="text-center">Slippage <small style="font-size: xx-small;">(Overall Accomplishment - Overall Target)</small></th>
                            <th class="text-center">Remarks</th>
                            <th class="text-center">Remaining % to be Accomplished <small style="font-size: xx-small;">(End of Quarter)</small></th>

                            <!-- <th class="text-center">Overall Target (%)</th>
                            <th class="text-center">Overall Accomplishment (%)</th> -->
                            <!-- <th class="text-center">Slippage <small style="font-size: xx-small;">(End of Quarter) <br> <small style="font-size: xx-small;">(Actual - Target)</small></small></th> -->
                            <th class="text-center">Design Monitoring Framework (DMF)</th>
                            <th class="text-center">Date Encoded</th>


                        </tr>
                    </thead>
                    <tbody>
                        @forelse($physicals->sortByDesc('created_at') as $physical)
                        <tr class="physical-row"
                            data-project-id="{{ $physical->project_id }}"
                            data-project-name="{{ $physical->project_name }}"
                            data-project-type="{{ $physical->project_type }}"
                            data-project-type1="{{ $physical->project_type1 }}"
                            data-project-weight="{{ $physical->weight }}"
                            data-actual="{{ $physical->actual }}"
                            data-target="{{ $physical->target }}"
                            data-project-weight1="{{ $physical->weight1 }}"
                            data-actual1="{{ $physical->actual1 }}"
                            data-target1="{{ $physical->target1 }}"
                            data-outcome-file="{{ $physical->outcome_file }}">
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2" style="align-items: center;">
                                    @if(auth()->user()->userlevel == -1 || auth()->user()->userlevel == 2 || auth()->user()->userlevel == 5)
                                    <a href="{{ route('projects.editFifthTab', $physical->id) }}" title="Edit Physical" role="button" aria-label="Edit Physical" style="margin-top: 3px;">
                                        <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit">
                                    </a>
                                    @endif
                                    @if(auth()->user()->userlevel == -1)
                                    <form action="{{ route('projects.destroyPhysical', $physical->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete Physical" style="background: none; border: none; padding-top: 7px; margin-top: 3px;">
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
                                                title: '<span style="font-size: medium;">Are you sure you want to delete this Physical Accomplishment permanently?</span>',
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
                            <!-- <td class="text-center">{{ $physical->quarter ?? 'N/A' }}</td>
                            <td class="text-center">{{ $physical->weight ?? 'N/A' }} %</td>
                            <td class="text-center">{{ $physical->actual ?? 'N/A' }} %</td>
                            <td class="text-center">{{ $physical->target ?? 'N/A' }} %</td> -->
                            <!-- <td class="text-center">{{ $physical->target_end_of_project ?? 'N/A' }} %</td> -->
                            <td class="text-center">{{ $physical->slippage ?? 'N/A' }} %</td>
                            <td class="text-center" style="color: {{ $physical->remarks === 'AHEAD' ? '#17a2b8' : ($physical->remarks === 'ON-TIME' ? '#28a745' : ($physical->remarks === 'BEHIND' ? '#dc3545' : ($physical->remarks === 'FOR VERIFICATION YEAR' ? '#ffd700' : ($physical->remarks === 'FOR VERIFICATION TARGET OR ACTUAL' ? '#ffd700' : 'inherit')))) }};"><strong>{{ $physical->remarks ?? 'N/A' }}</strong></td>
                            <td class="text-center"> {{ $physical->slippage_end_of_quarter ?? 'N/A' }} % </td>
                            <!-- <td class="text-center">{{ $physical->overall_target ?? 'N/A' }}</td>
                            <td class="text-center">{{ $physical->overall_accomplishment ?? 'N/A' }}</td> -->


                            <!-- <td class="text-center">{{ $physical->slippage_end_of_quarter ?? 'N/A' }}</td> -->
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
<script>
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
        });
    @endif
</script>
