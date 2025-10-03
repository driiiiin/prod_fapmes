<x-app-layout>
    <a href="{{ route('projects.index') }}" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
        <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
    </a>
    <div class="container mt-5">
        <div class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <div class="card-header" style="background-color: #296D98; color: white; padding: 10px;">
                <h4 class="mb-0 text-center"><strong>Edit Health Areas</strong></h4>
            </div>
            <div class="card-body p-2">
                <form action="{{ route('projects.updateThirdTab', $level->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" value="{{ $level->project_id }}" readonly style="font-size: small;">
                        </div>
                        <div class="col-md-9">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <input type="text" class="form-control border" id="project_name" name="project_name" value="{{ $level->project_name }}" readonly style="font-size: small;">
                        </div>
                        <div class="col-md-4">
                            <label for="level1" class="form-label" style="font-size: small;"><strong>Health Area (Level 1):</strong></label>
                            <select class="form-select border @error('level1') is-invalid @enderror"
                                id="level1"
                                name="level1"
                                style="font-size: small;">
                                <option value="" disabled hidden>-- Select Level 1 --</option>
                                <option value="N/A" {{ $level->level1 === 'N/A' ? 'selected' : '' }}>N/A</option>
                                @foreach (DB::table('ref_level1')->select('level1_desc')->orderBy('level1_desc', 'asc')->get() as $level1)
                                <option value="{{ $level1->level1_desc }}" {{ $level->level1 === $level1->level1_desc ? 'selected' : '' }}>{{ $level1->level1_desc }}</option>
                                @endforeach
                            </select>
                            @error('level1')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-4" id="level2-container">
                            <label for="level2" class="form-label" style="font-size: small;"><strong>Health Area (Level 2):</strong></label>
                            <select class="form-select border @error('level2') is-invalid @enderror"
                                id="level2"
                                name="level2"
                                style="font-size: small;">
                                <option value="" disabled hidden>-- Select Level 2 --</option>
                            </select>
                            @error('level2')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <script>
                            $(document).ready(function() {
                                $('#level1').change(function() {
                                    var level1Value = $(this).val();
                                    $('#level2').empty();
                                    $('#level2').append('<option value="" selected disabled hidden>-- Select Level 2 --</option>');
                                    $('#level2').append('<option value="">N/A</option>');

                                    if (level1Value === "") {
                                        return;
                                    }

                                    if (level1Value === "N/A") {
                                        $('#level2').val("");
                                        return;
                                    }

                                    $.ajax({
                                        url: '/get-level2-options',
                                        type: 'GET',
                                        data: {
                                            level1: level1Value
                                        },
                                        success: function(data) {
                                            $.each(data, function(index, level2) {
                                                $('#level2').append('<option value="' + level2.level2_desc + '">' + level2.level2_desc + '</option>');
                                            });

                                            // Set the selected value for level2 if it exists
                                            $('#level2').val("{{ $level->level2 }}");
                                        },
                                        error: function(xhr) {
                                            console.error(xhr);
                                        }
                                    });
                                });

                                // Trigger change event to populate level2 on page load
                                $('#level1').val("{{ $level->level1 }}").change();
                            });
                        </script>
                        <div class="col-md-4">
                            <label for="level3" class="form-label" style="font-size: small;"><strong>Health Systems Building Blocks: <span style="color: red">*</span></strong></label>
                            <select class="form-select border @error('level3') is-invalid @enderror"
                                id="level3"
                                name="level3"
                                required
                                style="font-size: small;">
                                <option value="" disabled hidden>-- Select Health Systems Building Blocks --</option>
                                @foreach (DB::table('ref_level3')->select('level3_desc')->orderBy('level3_desc', 'asc')->get() as $level3)
                                <option value="{{ $level3->level3_desc }}" {{ $level->level3 === $level3->level3_desc ? 'selected' : '' }}>{{ $level3->level3_desc }}</option>
                                @endforeach
                            </select>
                            @error('level3')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-3">
                            <label for="l_budget" class="form-label" style="font-size: small;"><strong>Level Budget (Php): <span style="color: red;">*</span></strong></label>
                            <input type="number"
                                class="form-control border @error('l_budget') is-invalid @enderror"
                                id="l_budget"
                                name="l_budget"
                                value="{{ $level->l_budget }}"
                                required
                                step="0.01"
                                style="font-size: small;">
                            @error('l_budget')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
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
                        <div class="col-md-4">
                            <label for="outcome" class="form-label" style="font-size: small;"><strong>Remarks:</strong></label>
                            <input type="text" class="form-control border" id="outcome" name="outcome" value="{{ $level->outcome }}" style="font-size: small;">
                        </div>

                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary">Update Health Areas</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>