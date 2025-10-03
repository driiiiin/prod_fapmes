<x-app-layout>
    <a href="{{ route('projects.index') }}" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
        <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
    </a>
    <div class="container mt-5">
        <div class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <div class="card-header" style="background-color: #296D98; color: white; padding: 10px;">
                <h4 class="mb-0 text-center"><strong>Edit Project Details</strong></h4>
            </div>
            <div class="card-body p-2">
                <form action="{{ route('projects.update', $project->id) }}" enctype="multipart/form-data" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-2">
                        <div class="col-md-2">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID: <span style="color: red;">*</span></strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" required style="font-size: small;" pattern="^([0-9]{2}-){3}[0-9]{1,3}$" title="Format: XX-XX-XX-0 or XX-XX-XX-000" value="{{ old('project_id', $project->project_id) }}" readonly>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project ID in the format XX-XX-XX-XX.
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
                            <input type="text" class="form-control border" id="project_name" name="project_name" required style="font-size: small;" value="{{ old('project_name', $project->project_name) }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project name.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="short_title" class="form-label" style="font-size: small;"><strong>Short Title <small>(ex. JICA, ADB, WB, WHO)</small>:</strong></label>
                            <input type="text" class="form-control border" id="short_title" name="short_title" style="font-size: small;" value="{{ old('short_title', $project->short_title) }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a short title.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="funding_source" class="form-label" style="font-size: small;"><strong>Funding Source: <span style="color: red;">*</span></strong></label>
                            <div class="autocomplete-wrapper" style="position: relative;">
                                <input type="text" class="form-select border" id="funding_source_input" placeholder="-- Select Funding Source --" style="font-size: small;" autocomplete="off" required value="{{ old('funding_source', $project->funding_source ?? '') }}">
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
                        <div class="col-md-4">
                            <label for="depdev" class="form-label" style="font-size: small;"><strong>Department of Economy, Planning and Development (DePDev):</strong></label>
                            <select class="form-select border" id="depdev" name="depdev" style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_depdev')->select('depdev_code', 'depdev_desc')->get() as $depdev)
                                <option value="{{ $depdev->depdev_desc }}" {{ old('depdev', $project->depdev) == $depdev->depdev_desc ? 'selected' : '' }}>{{ $depdev->depdev_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select DEPDev.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="management" class="form-label" style="font-size: small;"><strong>Management:</strong></label>
                            <select class="form-select border" id="management" name="management" style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_management')->select('management_code', 'management_desc')->get() as $management)
                                <option value="{{ $management->management_desc }}" {{ old('management', $project->management) == $management->management_desc ? 'selected' : '' }}>{{ $management->management_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select Management.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="gph" class="form-label" style="font-size: small;"><strong>GPH Implemented:</strong></label>
                            <select class="form-select border" id="gph" name="gph" style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_gph')->select('gph_code', 'gph_desc')->get() as $gph)
                                <option value="{{ $gph->gph_desc }}" {{ old('gph', $project->gph) == $gph->gph_desc ? 'selected' : '' }}>{{ $gph->gph_desc }}</option>
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
                                <option value="{{ $funds_type->funds_type_desc }}" {{ old('fund_type', $project->fund_type) == $funds_type->funds_type_desc ? 'selected' : '' }}>{{ $funds_type->funds_type_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select fund type.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="desk_officer" class="form-label" style="font-size: small;"><strong>Desk Officer:</strong></label>
                            <input type="text" class="form-control border" id="desk_officer" name="desk_officer" style="font-size: small;" value="{{ old('desk_officer', $project->desk_officer) }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a desk officer.
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
                        </div>

                        <div class="col-md-2">
                            <label for="environmental" class="form-label" style="font-size: small;"><strong>Environmental and Social Risk:</strong></label>
                            <select class="form-select border" id="environmental" name="environmental" style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                <option value="N/A" {{ old('environmental', $project->environmental) == 'N/A' ? 'selected' : '' }}>N/A</option>
                                @foreach (DB::table('ref_environmental')->select('environmental_code', 'environmental_desc')->get() as $environmental)
                                <option value="{{ $environmental->environmental_desc }}" {{ old('environmental', $project->environmental) == $environmental->environmental_desc ? 'selected' : '' }}>{{ $environmental->environmental_desc }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label for="health_facility" class="form-label" style="font-size: small;"><strong>Health Facility:</strong></label>
                            <div class="border rounded p-2" style="height: 150px; overflow-y: auto; background: white;">
                                @foreach (DB::table('ref_health_facility')->select('health_facility_desc')->orderBy('health_facility_desc', 'asc')->get() as $health_facility)
                                <div class="form-check d-flex align-items-start mb-2" style="gap: 8px;">
                                    <input class="form-check-input mt-1" type="checkbox" value="{{ $health_facility->health_facility_desc }}" id="health_facility_{{ $health_facility->health_facility_desc }}" name="health_facility[]" {{ in_array($health_facility->health_facility_desc, old('health_facility', is_array($project->health_facility ?? null) ? $project->health_facility : (isset($project->health_facility) ? explode(', ', $project->health_facility) : []))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="health_facility_{{ $health_facility->health_facility_desc }}" style="font-size: small; line-height: 1.3;">
                                        {{ $health_facility->health_facility_desc }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label for="development_objectives" class="form-label" style="font-size: small;"><strong>Development Objectives: <span style="color: red;">*</span></strong></label>
                            <textarea class="form-control border" id="development_objectives" name="development_objectives" rows="3" style="font-size: small;">{{ old('development_objectives', $project->development_objectives) }}</textarea>
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
                                            <input class="form-check-input" type="checkbox" value="{{ $sector->sector_desc }}" id="sector_{{ $sector->sector_desc }}" name="sector[]" {{ in_array($sector->sector_desc, old('sector', explode(', ', $project->sector))) ? 'checked' : '' }} style="margin-left: 5px;">
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

                                <div class="col-md-2 mt-2 d-flex flex-column justify-content-between">
                                    <div class="row">
                                        <div class="col">

                                            <div class="col-md-12">
                                                <label for="status" class="form-label" style="font-size: small;"><strong>Status:</strong></label>
                                                <select class="form-select border" id="status" name="status" required style="font-size: small;">
                                                    <option value="" disabled hidden>-- Please Select --</option>
                                                    @foreach (DB::table('ref_status')->select('status_code', 'status_desc')->orderBy('status_code', 'asc')->get() as $status)
                                                    <option value="{{ $status->status_desc }}"
                                                        {{ old('status', $project->status ?? '') == $status->status_desc ? 'selected' : '' }}>
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
                                                    <option value="{{ $site->site_desc }}"
                                                        {{ old('sites', $project->sites ?? '') == $site->site_desc ? 'selected' : '' }}>
                                                        {{ $site->site_desc }}
                                                    </option>
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
                                        Accepted file types: PDF (Max size: 25MB)
                                    </small>
                                    @if($project->agreement)
                                    <div class="mt-2" style="font-size: small;">
                                        Current file: <a href="{{ Storage::url($project->agreement) }}" target="_blank" style="color: blue; text-decoration: underline;">View Agreement</a>
                                        <input type="hidden" name="existing_agreement" value="{{ $project->agreement }}">
                                    </div>
                                    @endif
                                    <div class="invalid-feedback" style="font-size: small;">
                                        Please upload an agreement file (PDF) up to 25MB.
                                    </div>
                                </div>

                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const selectAllRegions = document.getElementById('select_all_regions');
                                const regionCheckboxes = document.querySelectorAll('.region-checkbox');
                                const provinceCheckboxes = document.querySelectorAll('.province-checkbox');
                                const citymunCheckboxes = document.querySelectorAll('.citymun-checkbox');

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
                                function filterProvinces() {
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
                                    filterCities();
                                }

                                // Function to filter cities based on selected provinces
                                function filterCities() {
                                    const selectedProvinces = Array.from(provinceCheckboxes)
                                        .filter(checkbox => checkbox.checked)
                                        .map(checkbox => checkbox.value);

                                    citymunCheckboxes.forEach(citymun => {
                                        const citymunProvince = citymun.parentElement.getAttribute('data-province');
                                        if (selectedProvinces.includes(citymunProvince)) {
                                            citymun.parentElement.style.display = 'block';
                                        } else {
                                            citymun.parentElement.style.display = 'none';
                                            citymun.checked = false;
                                        }
                                    });
                                }

                                // Attach change event listeners to region checkboxes
                                regionCheckboxes.forEach(regionCheckbox => {
                                    regionCheckbox.addEventListener('change', function() {
                                        filterProvinces();
                                    });
                                });

                                // Attach change event listeners to province checkboxes
                                provinceCheckboxes.forEach(provinceCheckbox => {
                                    provinceCheckbox.addEventListener('change', function() {
                                        filterCities();
                                    });
                                });

                                // Initialize "All Regions" checkbox state
                                const allChecked = Array.from(regionCheckboxes).every(checkbox => checkbox.checked);
                                selectAllRegions.checked = allChecked;

                                // Initial filtering based on already checked checkboxes
                                filterProvinces();
                                filterCities();
                            });
                        </script>


                        <div class="row g-2">

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
                                        <input class="form-check-input region-checkbox" type="checkbox"
                                            value="{{ $region->regcode }}" id="region_{{ $region->regcode }}"
                                            name="site_specific_reg[]"
                                            {{ in_array($region->regcode, old('site_specific_reg', $selectedRegions)) ? 'checked' : '' }}
                                            style="margin-left: 5px;">
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
                                <div class="border rounded p-2" style="height: 150px; overflow-y: scroll;" id="province-container">
                                    @foreach (DB::table('ref_prov')->select('provcode', 'provname', 'regcode')->orderBy('provname', 'asc')->get() as $province)
                                    <div class="form-check province" data-region="{{ $province->regcode }}">
                                        <input class="form-check-input province-checkbox" type="checkbox" value="{{ $province->provcode }}" id="province_{{ $province->provcode }}" name="site_specific_prov[]" {{ in_array($province->provname, old('site_specific_prov', explode(', ', $project->site_specific_prov))) ? 'checked' : '' }}>
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
                                <div class="border rounded p-2" style="height: 150px; overflow-y: scroll;" id="citymun-container">
                                    @foreach (DB::table('ref_citymun')->select('citycode', 'cityname', 'regcode', 'provcode')->orderBy('cityname', 'asc')->get() as $citymun)
                                    <div class="form-check citymun" data-region="{{ $citymun->regcode }}" data-province="{{ $citymun->provcode }}">
                                        <input class="form-check-input citymun-checkbox" type="checkbox" value="{{ $citymun->cityname }}" id="citymun_{{ $citymun->citycode }}" name="site_specific_city[]" style="margin-left: 5px;"
                                            {{ in_array($citymun->cityname, old('site_specific_city', explode(', ', $project->site_specific_city))) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="citymun_{{ $citymun->citycode }}">
                                            {{ $citymun->cityname }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please select a city/municipality.
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="outcome" class="form-label" style="font-size: small;"><strong>Outcome:</strong></label>
                                <textarea class="form-control border" id="outcome" name="outcome" style="font-size: small; height: 100px;">{{ old('outcome', $project->outcome) }}</textarea>
                                <div class="invalid-feedback" style="font-size: small;">
                                    Please provide outcome.
                                </div>
                            </div>

                            <div class="text-center md-3 mt-3">
                                <button type="submit" class="btn btn-success btn-sm" name="update" title="Update Project">
                                    <i class="fa fa-pencil-alt" aria-hidden="true"></i> Update Project
                                </button>
                                <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Cancel</a>
                            </div>
                        </div>
                    </div>
                </form>
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
</x-app-layout>
