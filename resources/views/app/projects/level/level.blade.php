<div class="tab-pane fade" id="levels" role="tabpanel" aria-labelledby="levels-tab">
    <div class="container">
        <div id="input-fields" class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <form action="{{ route('projects.storeThirdTab') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #296D98; color: white; padding: 10px;">
                    <div class="flex-grow-1 text-center">
                        <h4 class="mb-0"><strong>Health Areas</strong></h4>
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
                            <input type="text" class="form-control border" id="project_id" name="project_id" required style="font-size: small;" readonly value="{{ old('project_id', $project_id ?? '') }}">
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
                        <div class="col-md-4">
                            <label for="level1" class="form-label" style="font-size: small;"><strong>Health Area (Level 1):</strong></label>
                            <select class="form-select border @error('level1') is-invalid @enderror"
                                id="level1"
                                name="level1"
                                required
                                style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Level 1 --</option>
                                <option value="">N/A</option>
                                @foreach (DB::table('ref_level1')->select('level1_desc')->orderBy('level1_desc', 'asc')->get() as $level1)
                                <option value="{{ $level1->level1_desc }}" {{ old('level1') == $level1->level1_desc ? 'selected' : '' }}>{{ $level1->level1_desc }}</option>
                                @endforeach
                            </select>
                            @error('level1')
                            <script>
                                Swal.fire({
                                    title: 'Error',
                                    text: '{{ $message }}',
                                    icon: 'error'
                                });
                            </script>
                            @enderror
                        </div>
                        <div class="col-md-4" id="level2-container">
                            <label for="level2" class="form-label" style="font-size: small;"><strong>Health Area (Level 2):</strong></label>
                            <select class="form-select border @error('level2') is-invalid @enderror"
                                id="level2"
                                name="level2"
                                required
                                style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Level 2 --</option>
                            </select>
                            @error('level2')
                            <script>
                                Swal.fire({
                                    title: 'Error',
                                    text: '{{ $message }}',
                                    icon: 'error'
                                });
                            </script>
                            @enderror
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#level1').change(function() {
                                    var level1Value = $(this).val();
                                    $('#level2').empty(); // Clear previous Level 2 options
                                    $('#level2').append('<option value="" selected disabled hidden>-- Select Level 2 --</option>'); // Reset Level 2 dropdown

                                    if (level1Value === "") {
                                        // If Level 1 is not selected, do nothing
                                        return;
                                    }

                                    if (level1Value === "N/A") {
                                        // If Level 1 is N/A, add N/A option to Level 2
                                        $('#level2').append('<option value="">N/A</option>');
                                        $('#level2').val(""); // Set N/A as selected
                                        return;
                                    }

                                    // Fetch Level 2 options via AJAX
                                    $.ajax({
                                        url: '/get-level2-options', // Your route to fetch Level 2 options
                                        type: 'GET',
                                        data: {
                                            level1: level1Value
                                        },
                                        success: function(data) {
                                            // Check if N/A exists in the options
                                            var hasNA = data.some(function(level2) {
                                                return level2.level2_desc === "N/A";
                                            });

                                            // Add N/A option only if it doesn't exist in the data
                                            if (!hasNA) {
                                                $('#level2').append('<option value="">N/A</option>');
                                            }

                                            // Add all options from the data
                                            $.each(data, function(index, level2) {
                                                $('#level2').append('<option value="' + level2.level2_desc + '">' + level2.level2_desc + '</option>');
                                            });
                                        },
                                        error: function(xhr) {
                                            console.error(xhr);
                                        }
                                    });
                                });
                            });
                        </script>
                        <div class="col-md-4">
                            <label for="level3" class="form-label" style="font-size: small;"><strong>Health Systems Building Blocks: <span style="color: red;">*</span></strong></label>
                            <select class="form-select border @error('level3') is-invalid @enderror"
                                id="level3"
                                name="level3"
                                required
                                style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Health Systems Building Blocks --</option>

                                @foreach (DB::table('ref_level3')->select('level3_desc')->orderBy('level3_desc', 'asc')->get() as $level3)
                                <option value="{{ $level3->level3_desc }}" {{ old('level3') == $level3->level3_desc ? 'selected' : '' }}>{{ $level3->level3_desc }}</option>
                                @endforeach
                            </select>
                            @error('level3')
                            <script>
                                Swal.fire({
                                    title: 'Error',
                                    text: '{{ $message }}',
                                    icon: 'error'
                                });
                            </script>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="l_budget" class="form-label" style="font-size: small;"><strong>Level Budget (in Php): <span style="color: red;">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('l_budget') is-invalid @enderror"
                                id="l_budget"
                                name="l_budget"
                                required
                                step="0.01"
                                style="font-size: small;"
                                value="{{ old('l_budget') }}">
                            @error('l_budget')
                            <script>
                                Swal.fire({
                                    title: 'Error',
                                    text: 'The level budget field is required',
                                    icon: 'error'
                                });
                            </script>
                            @enderror
                        </div>
                        <script>
                            const lBudgetInput = document.getElementById('l_budget');
                            const lBudgetFeedback = document.createElement('div');

                            lBudgetFeedback.className = 'l-budget-feedback';

                            lBudgetInput.parentNode.insertBefore(lBudgetFeedback, lBudgetInput.nextSibling);

                            lBudgetInput.addEventListener('input', function() {
                                const lBudget = lBudgetInput.value;

                                if (lBudget.length > 0) {
                                    if (!isNaN(lBudget) && lBudget > 0) {
                                        lBudgetFeedback.textContent = '₱' + Number(lBudget).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                        lBudgetFeedback.style.color = 'green';
                                    } else {
                                        lBudgetFeedback.textContent = 'Invalid budget';
                                        lBudgetFeedback.style.color = 'red';
                                    }
                                } else {
                                    lBudgetFeedback.textContent = '';
                                }
                            });
                        </script>

                        <div class="col-md-3">
                            <label for="outcome" class="form-label" style="font-size: small;"><strong>Remarks:</strong></label>
                            <textarea
                                class="form-control border @error('outcome') is-invalid @enderror"
                                id="outcome"
                                name="outcome"
                                required
                                style="font-size: small; height: 100px;">{{ old('outcome') }}</textarea>
                            @error('outcome')
                            <script>
                                Swal.fire({
                                    title: 'Error',
                                    text: '{{ $message }}',
                                    icon: 'error'
                                });
                            </script>
                            @enderror
                        </div>


                        <div class="col-md-12 text-center mt-3">
                            <button type="submit" class="btn btn-success btn-sm" name="add" title="Add New Level">
                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Health Area
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="col-md-12 text-center mt-3 mb-2">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">Health Areas</h4>
                </div>
                <table class="table table-striped table-bordered" id="level-table" style="width: 100%;">
                    <thead style="border-top: 1px solid #ccc;">
                        <tr class="text-center">
                            <th class="text-center">Action</th>
                            <!-- <th class="text-center">ID</th> -->
                            <th class="text-center" style="min-width: 200px;">Project ID</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center" style="min-width: 100px;">Health Area (Level 1)</th>
                            <th class="text-center" style="min-width: 100px;">Health Area (Level 2)</th>
                            <th class="text-center" style="min-width: 100px;">Health Systems Building Blocks</th>
                            <th class="text-center">Level Budget (in Php)</th>
                            <th class="text-center">Remarks</th>
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
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2" style="align-items: center;">
                                    @if(Auth::user()->userlevel == -1 || Auth::user()->userlevel == 2 || Auth::user()->userlevel == 5)
                                    <a href="{{ route('projects.editThirdTab', $level->id) }}" title="Edit Level" role="button" aria-label="Edit Level" style="margin-top: 3px;">
                                        <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit">
                                    </a>
                                    @endif
                                    @if(Auth::user()->userlevel == -1)
                                    <form action="{{ route('projects.destroyLevel', $level->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete Level" style="background: none; border: none; padding-top: 7px; margin-top: 3px;">
                                            <img src="{{ asset('images/delete.png') }}" width="15" height="18" alt="Delete">
                                        </button>
                                    </form>
                                    @endif
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
                            </td>
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
