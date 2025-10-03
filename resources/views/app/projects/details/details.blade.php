    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
        <div class="container">
            @if (auth()->user()->userlevel != 6)
            <div class="col-md-12 text-start mt-3">
                <button type="button" class="btn btn-primary btn-sm" id="toggle-inputs" title="Toggle Input Fields" style="background-color: #296D98;">
                    <i class="fa fa-eye" aria-hidden="true"></i> Add New Project
                </button>
            </div>
            @endif

            <div id="input-fields" class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
                <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" id="update_project_id" name="update_project_id" value="">
                    @foreach([
                    'project_id',
                    'project_name',
                    'short_title',
                    'funding_source',
                    'depdev',
                    'management',
                    'gph',
                    'fund_type',
                    'desk_officer',
                    'alignment',
                    'environmental',
                    'health_facility',
                    'development_objectives',
                    'sector',
                    'sites',
                    'agreement',
                    'site_specific_reg',
                    'site_specific_prov',
                    'site_specific_city',

                    'status',
                    'outcome',
                    ] as $field)
                    @error($field)
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: '{{ $message }}',
                        });
                    </script>
                    @enderror
                    @endforeach
                    <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #296D98; color: white; padding: 10px;">
                        <div class="flex-grow-1 text-center">
                            <h4 class="mb-0"><strong>Project Data Update</strong></h4>
                        </div>
                        <div class="text-end">
                            <button type="reset" class="btn btn-secondary btn-sm" title="Clear Inputs">
                                <i class="fa fa-eraser" aria-hidden="true"></i> Clear
                            </button>
                        </div>
                    </div>

                    <div class="card-body p-2">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID: <span style="color: red;">*</span></strong></label>
                                <input type="text" class="form-control border" id="project_id" name="project_id" required style="font-size: small;" pattern="^([0-9]{2}-){3}[0-9]{1,3}$" title="Format: XX-XX-XX-0 or XX-XX-XX-000" value="{{ old('project_id', $project->project_id ?? '') }}">
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please provide a project ID in the format XX-XX-XX-XX.
                                </div>
                                <div class="valid-feedback" style="font-size: small; color: green;">
                                    Valid project ID format!
                                </div>
                            </div>
                            <script>
                                const input = document.getElementById('project_id');

                                input.addEventListener('input', function() {
                                    let value = this.value.replace(/\D/g, '');
                                    if (value.length > 8) {
                                        value = value.slice(0, 8);
                                    }
                                    const formattedValue = value.match(/.{1,2}/g)?.join('-') || '';
                                    this.value = formattedValue;

                                    // Validate the format
                                    const pattern = /^\d{2}-\d{2}-\d{2}-\d{2}$/;
                                    const isValid = pattern.test(this.value);

                                    if (!isValid && this.value.length > 0) {
                                        this.classList.add('is-invalid');
                                        this.classList.remove('is-valid');
                                        this.setCustomValidity('Please enter a valid project ID in the format XX-XX-XX-XX');
                                    } else if (isValid) {
                                        this.classList.remove('is-invalid');
                                        this.classList.add('is-valid');
                                        this.setCustomValidity('');
                                    } else {
                                        this.classList.remove('is-invalid');
                                        this.classList.remove('is-valid');
                                        this.setCustomValidity('');
                                    }
                                });

                                // Also validate on form submission
                                input.closest('form').addEventListener('submit', function(e) {
                                    const pattern = /^\d{2}-\d{2}-\d{2}-\d{2}$/;
                                    const isValid = pattern.test(input.value);

                                    if (!isValid) {
                                        e.preventDefault();
                                        input.classList.add('is-invalid');
                                        input.classList.remove('is-valid');
                                        input.setCustomValidity('Please enter a valid project ID in the format XX-XX-XX-XX');
                                    } else {
                                        input.classList.remove('is-invalid');
                                        input.classList.add('is-valid');
                                    }
                                });
                            </script>
                            <div class="col-md-7">
                                <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name: <span style="color: red;">*</span></strong></label>
                                <input type="text" class="form-control border" id="project_name" name="project_name" required style="font-size: small;" value="{{ old('project_name', $project->project_name ?? '') }}">
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please provide a project name.
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="short_title" class="form-label" style="font-size: small;"><strong>Short Title <small>(ex. JICA, ADB, WB, WHO)</small>: <span style="color: red;">*</span></strong></label>
                                <input type="text" class="form-control border" id="short_title" name="short_title" required style="font-size: small;" value="{{ old('short_title', $project->short_title ?? '') }}">
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please provide a short title.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="funding_source" class="form-label" style="font-size: small;"><strong>Funding Source: <span style="color: red;">*</span></strong></label>
                                <div class="autocomplete-wrapper" style="position: relative;">
                                    <input type="text" class="form-select border" id="funding_source_input" placeholder="-- Select Funding Source --" style="font-size: small;" autocomplete="off" required>
                                    <input type="hidden" id="funding_source" name="funding_source" value="{{ old('funding_source', $project->funding_source ?? '') }}" required>
                                    <div class="autocomplete-dropdown" id="funding_source_dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: white; border: 1px solid #ced4da; border-top: none; max-height: 200px; overflow-y: auto; z-index: 1000; border-radius: 0 0 0.375rem 0.375rem;">
                                        @foreach (DB::table('ref_funds')->select('funds_desc', 'funds_code')->get() as $funding_source)
                                        <div class="autocomplete-option" data-value="{{ $funding_source->funds_desc }}" style="padding: 0.375rem 0.75rem; cursor: pointer; font-size: small;" {{ old('funding_source', $project->funding_source ?? '') == $funding_source->funds_desc ? 'data-selected="true"' : '' }}>
                                            {{ $funding_source->funds_desc }}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select a funding source.
                                </div>
                            </div>
                            <!-- <div class="col-md-4">
                                <label for="donor" class="form-label" style="font-size: small;"><strong> Project Donor:</strong></label>
                                <select class="form-select border" id="donor" name="donor" required style="font-size: small;">
                                    <option value="" selected disabled hidden>-- Select Donor --</option>
                                </select>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select a donor.
                                </div>
                            </div>

                            <script>
                                document.getElementById('funding_source').addEventListener('change', function() {
                                    var fundingSource = this.value;
                                    var donorSelect = document.getElementById('donor');

                                    // Clear previous donor options
                                    donorSelect.innerHTML = '<option value="" selected disabled hidden>-- Select Donor --</option>';

                                    // Fetch donors based on the selected funding source
                                    fetch('/get-donors?funds_code=' + fundingSource)
                                        .then(response => response.json())
                                        .then(data => {
                                            data.forEach(function(donor) {
                                                var option = document.createElement('option');
                                                option.value = donor.funds_code; // Assuming funds_code is the value you want
                                                option.textContent = donor.funds_code; // Display text
                                                donorSelect.appendChild(option);
                                            });
                                        })
                                        .catch(error => console.error('Error fetching donors:', error));
                                });
                            </script> -->
                            <div class="col-md-4">
                                <label for="depdev" class="form-label" style="font-size: small;"><strong>Department of Economy, Planning and Development (DePDev):</strong></label>
                                <select class="form-select border" id="depdev" name="depdev" style="font-size: small;">
                                    <option value="" selected disabled hidden>-- Please Select --</option>
                                    @foreach (DB::table('ref_depdev')->select('depdev_code', 'depdev_desc')->get() as $depdev)
                                    <option value="{{ $depdev->depdev_desc }}" {{ old('depdev', $project->depdev ?? '') == $depdev->depdev_desc ? 'selected' : '' }}>{{ $depdev->depdev_desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="management" class="form-label" style="font-size: small;"><strong>Management:</strong></label>
                                <select class="form-select border" id="management" name="management" style="font-size: small;">
                                    <option value="" selected disabled hidden>-- Please Select --</option>
                                    @foreach (DB::table('ref_management')->select('management_code', 'management_desc')->get() as $management)
                                    <option value="{{ $management->management_desc }}" {{ old('management', $project->management ?? '') == $management->management_desc ? 'selected' : '' }}>{{ $management->management_desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select management.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="gph" class="form-label" style="font-size: small;"><strong>GPH Implemented:</strong></label>
                                <select class="form-select border" id="gph" name="gph" style="font-size: small;">
                                    <option value="" selected disabled hidden>-- Please Select --</option>
                                    @foreach (DB::table('ref_gph')->select('gph_code', 'gph_desc')->get() as $gph)
                                    <option value="{{ $gph->gph_desc }}" {{ old('gph', $project->gph ?? '') == $gph->gph_desc ? 'selected' : '' }}>{{ $gph->gph_desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select GPH Implemented.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="fund_type" class="form-label" style="font-size: small;"><strong>Fund Type: <span style="color: red;">*</span></strong></label>
                                <select class="form-select border" id="fund_type" name="fund_type" required style="font-size: small;">
                                    <option value="" selected disabled hidden>-- Please Select --</option>
                                    @foreach (DB::table('ref_funds_type')->select('funds_type_code', 'funds_type_desc')->get() as $funds_type)
                                    <option value="{{ $funds_type->funds_type_desc }}" {{ old('fund_type', $project->fund_type ?? '') == $funds_type->funds_type_desc ? 'selected' : '' }}>{{ $funds_type->funds_type_desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select fund type.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="desk_officer" class="form-label" style="font-size: small;"><strong>Desk Officer: <span style="color: red;">*</span></strong></label>
                                <input type="text" class="form-control border" id="desk_officer" name="desk_officer" style="font-size: small;" value="{{ old('desk_officer', $project->desk_officer ?? '') }}">
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please provide a desk_officer.
                                </div>
                            </div>

                            <div class="col-md-4">
                                <label for="alignment" class="form-label" style="font-size: small;"><strong>Alignment with 8PAA:</strong></label>
                                <div class="border rounded p-2" style="height: 180px; overflow-y: auto; background: white;">
                                    @foreach (DB::table('ref_alignment')->select('alignment_code', 'alignment_desc')->orderBy('alignment_code')->get() as $alignment)
                                    <div class="form-check d-flex align-items-start mb-2" style="gap: 8px;">
                                        <input class="form-check-input mt-1" type="checkbox" value="{{ $alignment->alignment_desc }}" id="alignment_{{ $alignment->alignment_code }}" name="alignment[]" {{ in_array($alignment->alignment_desc, old('alignment', is_array($project->alignment ?? null) ? $project->alignment : (isset($project->alignment) ? explode(', ', $project->alignment) : []))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="alignment_{{ $alignment->alignment_code }}" style="font-size: small; line-height: 1.3;">
                                            {{ $alignment->alignment_desc }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select alignment.
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="environmental" class="form-label" style="font-size: small;"><strong>Environmental and Social Risk:</strong></label>
                                <select class="form-select border" id="environmental" name="environmental" style="font-size: small;">
                                    <option value="" selected disabled hidden>-- Please Select --</option>
                                    <option value="N/A" {{ old('environmental', $project->environmental ?? '') == 'N/A' ? 'selected' : '' }}>N/A</option>
                                    @foreach (DB::table('ref_environmental')->select('environmental_code', 'environmental_desc')->get() as $environmental)
                                    <option value="{{ $environmental->environmental_desc }}" {{ old('environmental', $project->environmental ?? '') == $environmental->environmental_desc ? 'selected' : '' }}>{{ $environmental->environmental_desc }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select environmental.
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="health_facility" class="form-label" style="font-size: small;"><strong>Health Facility:</strong></label>
                                <div class="border rounded p-2" style="height: 150px; overflow-y: auto; background: white;">
                                    @foreach (DB::table('ref_health_facility')->select('health_facility_code', 'health_facility_desc')->orderBy('health_facility_code')->get() as $health_facility)
                                    <div class="form-check d-flex align-items-start mb-2" style="gap: 8px;">
                                        <input class="form-check-input mt-1" type="checkbox" value="{{ $health_facility->health_facility_desc }}" id="health_facility_{{ $health_facility->health_facility_code }}" name="health_facility[]" {{ in_array($health_facility->health_facility_desc, old('health_facility', is_array($project->health_facility ?? null) ? $project->health_facility : (isset($project->health_facility) ? explode(', ', $project->health_facility) : []))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="health_facility_{{ $health_facility->health_facility_code }}" style="font-size: small; line-height: 1.3;">
                                            {{ $health_facility->health_facility_desc }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select health facility.
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="development_objectives" class="form-label" style="font-size: small;"><strong>Development Objectives:<span style="color: red;">*</strong></label>
                                <textarea class="form-control border" id="development_objectives" name="development_objectives" rows="3" style="font-size: small;">{{ old('development_objectives', $project->development_objectives ?? '') }}</textarea>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please provide development objectives.
                                </div>
                            </div>


                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6 mt-2">
                                        <label for="sector" class="form-label" style="font-size: small;"><strong>Sector:</strong></label>
                                        <div class="border rounded p-2" style="height: 150px; overflow-y: auto;">
                                            @foreach (DB::table('ref_sectors')->select('sector_desc')->orderBy('sector_desc', 'asc')->get() as $sector)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="{{ $sector->sector_desc }}" id="sector_{{ $sector->sector_desc }}" name="sector[]" {{ in_array($sector->sector_desc, old('sector', [])) ? 'checked' : '' }} style="margin-left: 5px;">
                                                <label class="form-check-label" for="sector_{{ $sector->sector_desc }}">
                                                    {{ $sector->sector_desc }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        <div class="invalid-feedback" style="font-size: small;">
                                            Please select a sector.
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-2 d-flex flex-column justify-content-between">
                                        <div class="row">
                                            <div class="col">
                                                <div class="col-md-12">
                                                    <label for="status" class="form-label" style="font-size: small;"><strong>Status:</strong></label>
                                                    <select class="form-select border" id="status" name="status" required style="font-size: small;">
                                                        <option value="" disabled hidden>-- Please Select --</option>
                                                        @foreach (DB::table('ref_status')->select('status_code', 'status_desc')->orderBy('status_code', 'asc')->get() as $status)
                                                        <option value="{{ $status->status_desc }}"
                                                            {{ old('status', $project->status ?? 'Planning') == $status->status_code ? 'selected' : '' }}>
                                                            {{ $status->status_desc }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" style="font-size: small;">
                                                        Please select a status.
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mt-4">
                                                    <label for="sites" class="form-label" style="font-size: small;"><strong>Geographical Distribution: <span style="color: red;">*</span></strong></label>
                                                    <select class="form-select border" id="sites" name="sites" required style="font-size: small;">
                                                        <option value="" selected disabled hidden>-- Please Select --</option>
                                                        @foreach (DB::table('ref_site')->select('site_code', 'site_desc')->orderBy('site_code', 'asc')->get() as $site)
                                                        <option value="{{ $site->site_desc }}" {{ old('sites', $project->sites ?? '') == $site->site_code ? 'selected' : '' }}>{{ $site->site_desc }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="invalid-feedback" style="font-size: small;">
                                                        Please select sites.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-3 mt-2">
                                        <label for="agreement" class="form-label" style="font-size: small;">
                                            <strong>Agreement:</strong>
                                        </label>
                                        <input type="file"
                                            class="form-control border"
                                            id="agreement"
                                            name="agreement"
                                            style="font-size: small;"
                                            accept=".pdf"
                                            max="25600">
                                        <small class="text-muted" style="font-size: x-small;">
                                            <span style="color: #296D98;">Accepted file type: PDF (Max size: 25MB)</span>
                                        </small>
                                        <div class="invalid-feedback" style="font-size: small;">
                                            Please upload an agreement file (PDF).
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row g-2">
                                <!-- <div class="col-md-3">
                                    <label for="site_specific_reg" class="form-label" style="font-size: small;"><strong>Site Specific (Region):</strong></label>
                                    <div style="height: 150px; overflow-y: scroll;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select_all_regions" style="margin-left: 5px;">
                                            <label class="form-check-label" for="select_all_regions">
                                                All Regions
                                            </label>
                                        </div>
                                        @foreach (DB::table('ref_region')->select('regcode', 'nscb_reg_name')->orderBy('nscb_reg_name', 'asc')->get() as $region)
                                        <div class="form-check">
                                            <input class="form-check-input region-checkbox" type="checkbox" value="{{ $region->regcode }}" id="region_{{ $region->regcode }}" name="site_specific_reg[]" style="margin-left: 5px;">
                                            <label class="form-check-label" for="region_{{ $region->regcode }}">
                                                {{ $region->nscb_reg_name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback" style="font-size: small;">
                                        Please select a region.
                                    </div>
                                </div>

                                <script>
                                    document.getElementById('select_all_regions').addEventListener('change', function() {
                                        const checkboxes = document.querySelectorAll('.region-checkbox');
                                        checkboxes.forEach(checkbox => {
                                            checkbox.checked = this.checked;
                                        });
                                    });
                                </script> -->

                                <div class="col-md-3 mt-2">
                                    <label for="site_specific_reg" class="form-label" style="font-size: small;"><strong>Site Specific (Region):</strong></label>
                                    <div style="height: 150px; overflow-y: scroll;">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="select_all_regions" style="margin-left: 5px;">
                                            <label class="form-check-label" for="select_all_regions">
                                                All Regions
                                            </label>
                                        </div>
                                        @foreach (DB::table('ref_region')->select('regcode', 'nscb_reg_name')->orderBy('nscb_reg_name', 'asc')->get() as $region)
                                        <div class="form-check">
                                            <input class="form-check-input region-checkbox" type="checkbox" value="{{ $region->regcode }}" id="region_{{ $region->regcode }}" name="site_specific_reg[]" style="margin-left: 5px;">
                                            <label class="form-check-label" for="region_{{ $region->regcode }}">
                                                {{ $region->nscb_reg_name }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback" style="font-size: small;">
                                        Please select a region.
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <label for="site_specific_prov" class="form-label" style="font-size: small;"><strong>Site Specific (Province):</strong></label>
                                    <div style="height: 150px; overflow-y: scroll;" id="province-container">
                                        @foreach (DB::table('ref_prov')->select('provcode', 'provname', 'regcode')->orderBy('provname', 'asc')->get() as $province)
                                        <div class="form-check province" data-region="{{ $province->regcode }}" style="display: none;"> <!-- Initially hidden -->
                                            <input class="form-check-input province-checkbox" type="checkbox" value="{{ $province->provcode }}" id="province_{{ $province->provcode }}" name="site_specific_prov[]" style="margin-left: 5px;">
                                            <label class="form-check-label" for="province_{{ $province->provcode }}">
                                                {{ $province->provname }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback" style="font-size: small;">
                                        Please select a province.
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="site_specific_city" class="form-label" style="font-size: small;"><strong>Site Specific (City/Municipality):</strong></label>
                                    <div style="height: 150px; overflow-y: scroll;" id="citymun-container">
                                        @foreach (DB::table('ref_citymun')->select('citycode', 'cityname', 'regcode', 'provcode')->orderBy('cityname', 'asc')->get() as $citymun)
                                        <div class="form-check citymun" data-region="{{ $citymun->regcode }}" data-province="{{ $citymun->provcode }}" style="display: none;"> <!-- Initially hidden -->
                                            <input class="form-check-input citymun-checkbox" type="checkbox" value="{{ $citymun->cityname }}" id="citymun_{{ $citymun->cityname }}" name="site_specific_city[]" style="margin-left: 5px;">
                                            <label class="form-check-label" for="citymun_{{ $citymun->cityname }}">
                                                {{ $citymun->cityname }}
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="invalid-feedback" style="font-size: small;">
                                        Please select a city/municipality.
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const selectAllRegions = document.getElementById('select_all_regions');
                                        const regionCheckboxes = document.querySelectorAll('.region-checkbox');
                                        const provinceCheckboxes = document.querySelectorAll('.province-checkbox');
                                        const citymunCheckboxes = document.querySelectorAll('.citymun');

                                        // Handle "All Regions" checkbox
                                        selectAllRegions.addEventListener('change', function() {
                                            const isChecked = this.checked;
                                            regionCheckboxes.forEach(checkbox => {
                                                checkbox.checked = isChecked;
                                                // Trigger the change event to update provinces
                                                checkbox.dispatchEvent(new Event('change'));
                                            });
                                        });

                                        // Function to filter provinces based on selected regions
                                        regionCheckboxes.forEach(regionCheckbox => {
                                            regionCheckbox.addEventListener('change', function() {
                                                const selectedRegions = Array.from(regionCheckboxes)
                                                    .filter(checkbox => checkbox.checked)
                                                    .map(checkbox => checkbox.value);

                                                // Update "All Regions" checkbox state
                                                selectAllRegions.checked = selectedRegions.length === regionCheckboxes.length;

                                                provinceCheckboxes.forEach(provinceCheckbox => {
                                                    const provinceRegion = provinceCheckbox.parentElement.getAttribute('data-region');
                                                    if (selectedRegions.includes(provinceRegion)) {
                                                        provinceCheckbox.parentElement.style.display = 'block';
                                                    } else {
                                                        provinceCheckbox.parentElement.style.display = 'none';
                                                        provinceCheckbox.checked = false;
                                                    }
                                                });

                                                // Reset city municipalities when regions change
                                                citymunCheckboxes.forEach(citymun => {
                                                    citymun.style.display = 'none';
                                                    citymun.querySelector('input').checked = false;
                                                });
                                            });
                                        });

                                        // Function to filter cities based on selected provinces
                                        provinceCheckboxes.forEach(provinceCheckbox => {
                                            provinceCheckbox.addEventListener('change', function() {
                                                const selectedProvinces = Array.from(provinceCheckboxes)
                                                    .filter(checkbox => checkbox.checked)
                                                    .map(checkbox => checkbox.value); // Get the provcode of selected provinces
                                                citymunCheckboxes.forEach(citymun => {
                                                    const citymunProvince = citymun.getAttribute('data-province');
                                                    if (selectedProvinces.includes(citymunProvince)) {
                                                        citymun.style.display = 'block'; // Show city/municipality
                                                    } else {
                                                        citymun.style.display = 'none'; // Hide city/municipality
                                                        citymun.querySelector('input').checked = false; // Uncheck if hidden
                                                    }
                                                });
                                            });
                                        });
                                    });
                                </script>
                                <!-- <div class="col-md-3 d-flex flex-column justify-content-between" style="height: 150px;"> -->
                                <!-- <div>
                                        <label for="uhc" class="form-label" style="font-size: small;"><strong>UHC Class:</strong></label>
                                        <select class="form-select border" id="uhc" name="uhc" style="font-size: small;">
                                            <option value="" selected disabled hidden>-- Please Select --</option>
                                            @foreach (DB::table('ref_uhc')->select('uhc_code', 'uhc_desc')->get() as $uhc)
                                            <option value="{{ $uhc->uhc_desc }}">{{ $uhc->uhc_desc }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" style="font-size: small;">
                                            Please select a class.
                                        </div>
                                    </div> -->
                                <!-- <div style="padding-top: 10px;">
                                        <label class="form-label" style="font-size: small;"><strong>UHC_IS:</strong></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="1" id="uhc_is_yes" name="uhc_is">
                                            <label class="form-check-label" for="uhc_is_yes">
                                                Yes
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" value="0" id="uhc_is_no" name="uhc_is">
                                            <label class="form-check-label" for="uhc_is_no">
                                                No
                                            </label>
                                        </div>
                                        <div class="invalid-feedback" style="font-size: small;">
                                            Please select an option.
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-3">
                                    <label for="outcome" class="form-label" style="font-size: small;"><strong>Outcome:</strong></label>
                                    <textarea class="form-control border" id="outcome" name="outcome" style="font-size: small; height: 100px;">{{ old('outcome', $project->outcome ?? '') }}</textarea>
                                    <div class="invalid-feedback" style="font-size: small;">
                                        Please provide outcome.
                                    </div>
                                </div>

                                <!-- @php
                                    $totalBudgetByGph = App\Models\Project::getTotalBudgetByGphImplemented();
                                @endphp
                                <div class="col-md-3">
                                    <label for="total_budget" class="form-label" style="font-size: small;"><strong>Total Budget:</strong></label>
                                    <input type="text" class="form-control border" id="total_budget" name="total_budget" value="{{ $totalBudgetByGph }}" style="font-size: small;">
                                </div> -->

                                <div class="text-center md-3 mt-5">
                                    <button type="submit" class="btn btn-success btn-sm" name="add" title="Add New Project">
                                        <i class="fa fa-plus-circle" aria-hidden="true"></i> Save Project
                                    </button>
                                    <!-- <button type="submit" class="btn btn-primary btn-sm" name="update" title="Update Project">
                                        <i class="fa fa-pencil-alt" aria-hidden="true"></i> Update
                                    </button> -->
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <div class="table-wrapper">
                <div class="col-md-12 text-center mt-3 mb-2">
                    <h4 style="font-size: 1.2rem; font-weight: bold;">LIST OF PROJECTS</h4>
                </div>
                <table class="table table-striped table-bordered" id="projects-table" style="width: 100%;">
                    <thead style="border-top: 1px solid #ccc; background-color: #f0f0f0;">
                        <tr class="text-center">
                            <!-- <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Action</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">ID</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Project ID</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Project Name</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Short Title</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Funding Source</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 12px;">International Health Partners</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">DEPDev</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Management</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">GPH Implemented</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Fund Type</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Fund Management</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Desk Officer</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Alignment with 8PAA</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 12px;">Environmental and Social Risk</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Health Facility</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Development Objectives</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Sector</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Agreement</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Status</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Geographical Distribution</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Region</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Province</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">City / Municipality</th>
                            <th class="text-center" style="background-color: #36454F !important; color: #fff; font-size: 14px;">Outcome</th> -->
                            <th class="text-center">Action</th>
                            <!-- <th class="text-center">ID</th> -->
                            <th class="text-center">Project ID</th>
                            <th class="text-center">Project Name</th>
                            <th class="text-center">Short Title</th>
                            <th class="text-center">Funding Source</th>
                            <th class="text-center">International Health Partners</th>
                            <th class="text-center">DEPDev</th>
                            <th class="text-center">Management</th>
                            <th class="text-center">GPH Implemented</th>
                            <th class="text-center">Fund Type</th>
                            <th class="text-center">Fund Management</th>
                            <th class="text-center">Desk Officer</th>
                            <th class="text-center">Alignment with 8PAA</th>
                            <th class="text-center">Environmental and Social Risk</th>
                            <th class="text-center">Health Facility</th>
                            <th class="text-center">Development Objectives</th>
                            <th class="text-center">Sector</th>
                            <th class="text-center">Agreement</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Geographical Distribution</th>
                            <th class="text-center">Region</th>
                            <th class="text-center">Province</th>
                            <th class="text-center">City / Municipality</th>
                            <th class="text-center">Outcome</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects->sortByDesc('created_at') as $project)
                        <tr class="project-row"
                            data-project-id="{{ $project->project_id }}"
                            data-project-name="{{ $project->project_name }}"
                            data-short-title="{{ $project->short_title }}"
                            data-funding-source="{{ $project->funding_source }}"
                            data-donor="{{ $project->donor }}"
                            data-depdev="{{ $project->depdev }}"
                            data-management="{{ $project->management }}"
                            data-gph="{{ $project->gph }}"
                            data-fund-type="{{ $project->fund_type }}"
                            data-desk-officer="{{ $project->desk_officer }}"
                            data-alignment="{{ $project->alignment }}"
                            data-environmental="{{ $project->environmental }}"
                            data-health-facility="{{ $project->health_facility }}"
                            data-development-objectives="{{ $project->development_objectives }}"
                            data-sector="{{ $project->sector }}"
                            data-sites="{{ $project->sites }}"
                            data-region="{{ $project->site_specific_reg }}"
                            data-province="{{ $project->site_specific_prov }}"
                            data-city="{{ $project->site_specific_city }}"
                            data-status="{{ $project->status }}"
                            data-outcome="{{ $project->outcome }}">
                            <td class="text-center" style="font-size: small;">
                                <div class="d-flex justify-content-center align-items-center gap-2" style="align-items: center;">

                                    <a href="{{ route('projects.show', $project->id) }}" title="View Project">
                                        <img src="{{ asset('images/view.png') }}" width="15" height="15" alt="View">
                                    </a>
                                    @if (Auth()->user()->userlevel == -1 || Auth()->user()->userlevel == 2 || Auth()->user()->userlevel == 5 )
                                    <a href="{{ route('projects.edit', $project->id) }}" title="Edit Project">
                                        <img src="{{ asset('images/edit.png') }}" width="15" height="15" alt="Edit">
                                    </a>
                                    @endif
                                    @if (Auth()->user()->userlevel == -1)
                                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST" class="delete-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="delete-button" title="Delete Project" style="background: none; border: none; padding-top: 7px;">
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
                                                title: '<span style="font-size: medium;">Are you sure you want to delete this project permanently?</span>',
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
                            <!-- <td class="text-center">{{ $project->id }}</td> -->
                            <td style="min-width: 150px;" class="text-center">{{ $project->project_id }}</td>
                            <td style="min-width: 700px;" class="text-center">{{ $project->project_name }}</td>
                            <td style="min-width: 120px;" class="text-center">{{ $project->short_title }}</td>
                            <td style="min-width: 300px;" class="text-center">{{ $project->funding_source }}</td>
                            <td style="min-width: 150px;" class="text-center">{{ $project->donor }}</td>
                            <td class="text-center">{{ $project->depdev }}</td>
                            <td class="text-center">{{ $project->management }}</td>
                            <td class="text-center">{{ $project->gph }}</td>
                            <td class="text-center">{{ $project->fund_type }}</td>
                            <td class="text-center">{{ $project->fund_management }}</td>
                            <td class="text-center">{{ $project->desk_officer }}</td>
                            <td class="text-center">{{ $project->alignment }}</td>
                            <td class="text-center">{{ $project->environmental }}</td>
                            <td class="text-center">{{ $project->health_facility }}</td>
                            <td class="text-center">{{ $project->development_objectives }}</td>
                            <td style="min-width: 250px;" class="text-center">{{ $project->sector }}</td>
                            <td class="text-center">
                                @if($project->agreement)
                                <a href="{{ Storage::url($project->agreement) }}" target="_blank" style="color: blue; text-decoration: underline;">View Agreement</a>
                                @else
                                N/A
                                @endif
                            </td>
                            <td class="text-center">{{ $project->status }}</td>
                            <td class="text-center">{{ $project->sites }}</td>
                            <td style="min-width: 250px;" class="text-center">{{ $project->site_specific_reg }}</td>
                            <td style="min-width: 250px;" class="text-center">{{ $project->site_specific_prov }}</td>
                            <td style="min-width: 250px;" class="text-center">{{ $project->site_specific_city }}</td>
                            <td style="min-width: 400px;" class="text-center">{{ $project->outcome }}</td>
                        </tr>
                        @empty
                        <!-- <tr>
                                <td colspan="19" class="text-center">No project found.</td>
                            </tr> -->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.getElementById('agreement').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.size > 25 * 1024 * 1024) { // 25MB in bytes
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Your file is above 25MB. Please select a smaller file.',
                });
                event.target.value = ''; // Clear the input
            }
        });

        // Autocomplete functionality for funding source
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('funding_source_input');
            const hiddenInput = document.getElementById('funding_source');
            const dropdown = document.getElementById('funding_source_dropdown');
            const options = dropdown.querySelectorAll('.autocomplete-option');

            // Set initial value if there's a selected option
            const selectedOption = dropdown.querySelector('[data-selected="true"]');
            if (selectedOption) {
                input.value = selectedOption.textContent.trim();
            }

            // Show dropdown on focus
            input.addEventListener('focus', function() {
                dropdown.style.display = 'block';
                filterOptions('');
            });

            // Hide dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.style.display = 'none';
                }
            });

            // Filter options on input
            input.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                filterOptions(searchTerm);
                dropdown.style.display = 'block';
            });

            // Handle option selection
            options.forEach(option => {
                option.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');
                    input.value = this.textContent.trim();
                    hiddenInput.value = value;
                    dropdown.style.display = 'none';

                    // Trigger change event for form validation
                    const event = new Event('change', { bubbles: true });
                    hiddenInput.dispatchEvent(event);
                });

                // Hover effect
                option.addEventListener('mouseenter', function() {
                    this.style.backgroundColor = '#f8f9fa';
                });

                option.addEventListener('mouseleave', function() {
                    this.style.backgroundColor = '';
                });
            });

            // Handle keyboard navigation
            input.addEventListener('keydown', function(e) {
                const visibleOptions = Array.from(options).filter(opt => opt.style.display !== 'none');
                const currentIndex = visibleOptions.findIndex(opt => opt.style.backgroundColor === 'rgb(248, 249, 250)');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    const nextIndex = currentIndex < visibleOptions.length - 1 ? currentIndex + 1 : 0;
                    visibleOptions.forEach(opt => opt.style.backgroundColor = '');
                    if (visibleOptions[nextIndex]) {
                        visibleOptions[nextIndex].style.backgroundColor = '#f8f9fa';
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    const prevIndex = currentIndex > 0 ? currentIndex - 1 : visibleOptions.length - 1;
                    visibleOptions.forEach(opt => opt.style.backgroundColor = '');
                    if (visibleOptions[prevIndex]) {
                        visibleOptions[prevIndex].style.backgroundColor = '#f8f9fa';
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    const highlightedOption = visibleOptions.find(opt => opt.style.backgroundColor === 'rgb(248, 249, 250)');
                    if (highlightedOption) {
                        highlightedOption.click();
                    }
                } else if (e.key === 'Escape') {
                    dropdown.style.display = 'none';
                }
            });

            function filterOptions(searchTerm) {
                options.forEach(option => {
                    const text = option.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        option.style.display = 'block';
                    } else {
                        option.style.display = 'none';
                    }
                });
            }
        });
    </script>
