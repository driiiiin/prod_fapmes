<x-app-layout>
    <a href="{{ route('projects.index') }}" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
        <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
    </a>
    <div class="container mt-5">
        <form action="{{ route('projects.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf
            @foreach([
            'project_id',
            'project_name',
            'short_title',
            'funding_source',
            'donor',
            'depdev',
            'management',
            'gph',
            'fund_type',
            'fund_management',
            'desk_officer',
            'sector',
            'sites',
            'agreement',
            'site_specific',
            'classification',
            'status',
            'outcome',
            ] as $field)
            @error($field)
            <div class="alert alert-danger mb-1 mt-1">{{ $message }}</div>
            @enderror
            @endforeach
            <div class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
                <div class="card-header" style="background-color: #296D98; color: white; padding: 10px;">
                    <h4 class="mb-0 text-center"><strong>Create Project</strong></h4>
                </div>
                <div class="card-body p-2">
                    <div class="row g-2">
                        <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold;">PROJECT DETAILS</h4>
                            <hr style="border-top: 3px solid #296D98;">
                        </div>
                        <div class="col-md-2">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" required style="font-size: small;" pattern="\w{2}-\w{2}-\w{2}-\w{2}" title="Format: XX-XX-XX-XX" value="{{ old('project_id') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project ID in the format XX-XX-XX-XX.
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <input type="text" class="form-control border" id="project_name" name="project_name" required style="font-size: small;" value="{{ old('project_name') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a project name.
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label for="short_title" class="form-label" style="font-size: small;"><strong>Short Title <small>(ex. JICA, ADB, WB, WHO)</small>:</strong></label>
                            <input type="text" class="form-control border" id="short_title" name="short_title" required style="font-size: small;" value="{{ old('short_title') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a short title.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="funding_source" class="form-label" style="font-size: small;"><strong>Funding Source:</strong></label>
                            <select class="form-select border" id="funding_source" name="funding_source" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Funding Source --</option>
                                @foreach (DB::table('ref_funds')->select('funds_desc')->get() as $funding_source)
                                <option value="{{ $funding_source->funds_desc }}" {{ old('funding_source') == $funding_source->funds_desc ? 'selected' : '' }}>{{ $funding_source->funds_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select a funding source.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="donor" class="form-label" style="font-size: small;"><strong>Donor:</strong></label>
                            <select class="form-select border" id="donor" name="donor" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Donor --</option>
                                @foreach (DB::table('ref_funds')->select('funds_code')->get() as $donor)
                                <option value="{{ $donor->funds_code }}" {{ old('donor') == $donor->funds_code ? 'selected' : '' }}>{{ $donor->funds_code }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select a donor.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="depdev" class="form-label" style="font-size: small;"><strong>DEP Dev:</strong></label>
                            <select class="form-select border" id="depdev" name="depdev" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_depdev')->select('depdev_code', 'depdev_desc')->get() as $depdev)
                                <option value="{{ $depdev->depdev_desc }}" {{ old('depdev') == $depdev->depdev_desc ? 'selected' : '' }}>{{ $depdev->depdev_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="management" class="form-label" style="font-size: small;"><strong>Management:</strong></label>
                            <select class="form-select border" id="management" name="management" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_management')->select('management_code', 'management_desc')->get() as $management)
                                <option value="{{ $management->management_desc }}" {{ old('management') == $management->management_desc ? 'selected' : '' }}>{{ $management->management_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select management.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="gph" class="form-label" style="font-size: small;"><strong>GPH Implemented:</strong></label>
                            <select class="form-select border" id="gph" name="gph" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_gph')->select('gph_code', 'gph_desc')->get() as $gph)
                                <option value="{{ $gph->gph_desc }}" {{ old('gph') == $gph->gph_desc ? 'selected' : '' }}>{{ $gph->gph_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select GPH Implemented.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="fund_type" class="form-label" style="font-size: small;"><strong>Fund Type:</strong></label>
                            <select class="form-select border" id="fund_type" name="fund_type" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_funds_type')->select('funds_type_code', 'funds_type_desc')->get() as $funds_type)
                                <option value="{{ $funds_type->funds_type_desc }}" {{ old('fund_type') == $funds_type->funds_type_desc ? 'selected' : '' }}>{{ $funds_type->funds_type_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select fund type.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="manager" class="form-label" style="font-size: small;"><strong>Desk Officer:</strong></label>
                            <input type="text" class="form-control border" id="manager" name="manager" required style="font-size: small;" value="{{ old('manager') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a manager.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="sector" class="form-label" style="font-size: small;"><strong>Sector:</strong></label>
                            <select class="form-select border" id="sector" name="sector" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Please Select --</option>
                                @foreach (DB::table('ref_sectors')->select('sector_code', 'sector_desc')->get() as $sector)
                                <option value="{{ $sector->sector_desc }}" {{ old('sector') == $sector->sector_desc ? 'selected' : '' }}>{{ $sector->sector_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select a sector.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="sites" class="form-label" style="font-size: small;"><strong>Sites:</strong></label>
                            <input type="text" class="form-control border" id="sites" name="sites" required style="font-size: small;" value="{{ old('sites') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide sites.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="agreement" class="form-label" style="font-size: small;"><strong>Agreement:</strong></label>
                            <input type="text" class="form-control border" id="agreement" name="agreement" required style="font-size: small;" value="{{ old('agreement') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide an agreement.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="site_specific" class="form-label" style="font-size: small;"><strong>Site Specific:</strong></label>
                            <input type="text" class="form-control border" id="site_specific" name="site_specific" required style="font-size: small;" value="{{ old('site_specific') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide site specific.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="classification" class="form-label" style="font-size: small;"><strong>Classification:</strong></label>
                            <input type="text" class="form-control border" id="classification" name="classification" required style="font-size: small;" value="{{ old('classification') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a classification.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="status" class="form-label" style="font-size: small;"><strong>Status:</strong></label>
                            <input type="text" class="form-control border" id="status" name="status" required style="font-size: small;" value="{{ old('status') }}">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a status.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="outcome" class="form-label" style="font-size: small;"><strong>Outcome:</strong></label>
                            <textarea class="form-control border" id="outcome" name="outcome" required style="font-size: small; height: 100px;">{{ old('outcome') }}</textarea>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide outcome.
                            </div>
                        </div>
                        <!-- <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold;">IMPLEMENTATION SCHEDULE</h4>
                            <hr style="border-top: 3px solid #296D98;">
                        </div> -->
                        <!-- <div class="col-md-3">
                            <label for="start_date" class="form-label" style="font-size: small;"><strong>Start Date:</strong></label>
                            <input type="date" class="form-control border" id="start_date" name="start_date" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a start date.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="interim_date" class="form-label" style="font-size: small;"><strong>Interim Date:</strong></label>
                            <input type="date" class="form-control border" id="interim_date" name="interim_date" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide an interim date.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="end_date" class="form-label" style="font-size: small;"><strong>End Date:</strong></label>
                            <input type="date" class="form-control border" id="end_date" name="end_date" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide an end date.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="extension" class="form-label" style="font-size: small;"><strong>Extension:</strong></label>
                            <input type="date" class="form-control border" id="extension" name="extension" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide an extension.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="duration" class="form-label" style="font-size: small;"><strong>Duration:</strong></label>
                            <input type="number" class="form-control border" id="duration" name="duration" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a duration.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="time_elapsed" class="form-label" style="font-size: small;"><strong>Time Elapsed:</strong></label>
                            <input type="number" class="form-control border" id="time_elapsed" name="time_elapsed" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide time elapsed.
                            </div>
                        </div> -->
                        <!-- <div class="col-md-4">
                            <label for="p_time_elapsed" class="form-label" style="font-size: small;"><strong>Percentage of Time Elapsed:</strong></label>
                            <input type="text" class="form-control border" id="p_time_elapsed" name="p_time_elapsed" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide percentage of time elapsed.
                            </div>
                        </div> -->
                        <!-- <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold;">FINANCIAL ACCOMPLISHMENT</h4>
                            <hr style="border-top: 3px solid #296D98;">
                        </div>
                        <div class="col-md-3">
                            <label for="budget" class="form-label" style="font-size: small;"><strong>Budget:</strong></label>
                            <input type="number" class="form-control border" id="budget" name="budget" required style="font-size: small;" step="0.01" min="0" placeholder="0.00">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a valid budget amount.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="orig_budget" class="form-label" style="font-size: small;"><strong>Budget (Original Currency):</strong></label>
                            <input type="text" class="form-control border" id="orig_budget" name="orig_budget" required style="font-size: small;" step="0.01" min="0" placeholder="0.00">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide the original budget.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="lp" class="form-label" style="font-size: small;"><strong>LP:</strong></label>
                            <input type="number" class="form-control border" id="lp" name="lp" required style="font-size: small;" step="0.01" min="0" placeholder="0.00">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide LP.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="gph_counterpart" class="form-label" style="font-size: small;"><strong>GPH Counterpart:</strong></label>
                            <input type="text" class="form-control border" id="gph_counterpart" name="gph_counterpart" required style="font-size: small;" step="0.01" min="0" placeholder="0.00">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a GPH counterpart.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="disbursement" class="form-label" style="font-size: small;"><strong>Disbursement:</strong></label>
                            <input type="number" class="form-control border" id="disbursement" name="disbursement" required style="font-size: small;" step="0.01" min="0" placeholder="0.00">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide disbursement.
                            </div>
                        </div> -->
                        <!-- <div class="col-md-4">
                            <label for="p_disbursement" class="form-label" style="font-size: small;"><strong>Percentage of Disbursement:</strong></label>
                            <input type="text" class="form-control border" id="p_disbursement" name="p_disbursement" required style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide percentage of disbursement.
                            </div>
                        </div> -->
                        <!-- <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold;">PHYSICAL ACCOMPLISHMENT</h4>
                            <hr style="border-top: 3px solid #296D98;">
                        </div>
                        <div class="col-md-4">
                            <label for="actual" class="form-label" style="font-size: small;"><strong>Actual:</strong></label>
                            <input type="number" class="form-control border" id="actual" name="actual" required step="0.01" style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide actual.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="target" class="form-label" style="font-size: small;"><strong>Target:</strong></label>
                            <input type="number" class="form-control border" id="target" name="target" required step="0.01" style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide target.
                            </div>
                        </div> -->
                        <!-- <div class="col-md-3">
                            <label for="p_accomplishment" class="form-label" style="font-size: small;"><strong>Percentage of Accomplishment:</strong></label>
                            <input type="number" class="form-control border" id="p_accomplishment" name="p_accomplishment" required step="0.01" style="font-size: small;">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide percentage of accomplishment.
                            </div>
                        </div> -->
                        <!-- <div class="col-md-12 text-center mt-3 mb-2">
                            <h4 style="font-size: 1.2rem; font-weight: bold;">PROJECT Levels</h4>
                            <hr style="border-top: 3px solid #296D98;">
                        </div>
                        <div class="col-md-4">
                            <label for="level1" class="form-label" style="font-size: small;"><strong>Level 1:</strong></label>
                            <select class="form-select border" id="level1" name="level1" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Level 1 --</option>
                                @foreach (DB::table('ref_level1')->select('level1_desc')->get() as $level1)
                                <option value="{{ $level1->level1_desc }}" {{ old('level1') == $level1->level1_desc ? 'selected' : '' }}>{{ $level1->level1_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select a valid option for Level 1.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="level2" class="form-label" style="font-size: small;"><strong>Level 2:</strong></label>
                            <select class="form-select border" id="level2" name="level2" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Level 2 --</option>
                                @foreach (DB::table('ref_level2')->select('level2_desc')->get() as $level2)
                                <option value="{{ $level2->level2_desc }}" {{ old('level2') == $level2->level2_desc ? 'selected' : '' }}>{{ $level2->level2_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select a valid option for Level 2.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="level3" class="form-label" style="font-size: small;"><strong>Level 3:</strong></label>
                            <select class="form-select border" id="level3" name="level3" required style="font-size: small;">
                                <option value="" selected disabled hidden>-- Select Level 3 --</option>
                                @foreach (DB::table('ref_level3')->select('level3_desc')->get() as $level3)
                                <option value="{{ $level3->level3_desc }}" {{ old('level3') == $level3->level3_desc ? 'selected' : '' }}>{{ $level3->level3_desc }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please select a valid option for Level 3.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="outcome" class="form-label" style="font-size: small;"><strong>Outcome:</strong></label>
                            <textarea class="form-control border" id="outcome" name="outcome" required style="font-size: small; height: 100px;">{{ old('outcome') }}</textarea>
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide outcome.
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label for="l_budget" class="form-label" style="font-size: small;"><strong>Budget:</strong></label>
                            <input type="number" class="form-control border" id="l_budget" name="l_budget" required style="font-size: small;" step="0.01" min="0" placeholder="0.00">
                            <div class="invalid-feedback" style="font-size: small;">
                                Please provide a valid amount for Budget.
                            </div>
                        </div> -->

                        <div class="col-md-12 text-center mt-3">
                            <x-primary-button class="ms-4">
                                {{ __('Submit') }}
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>



</x-app-layout>
