<x-app-layout>
    <a href="{{ route('projects.index') }}" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
        <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
    </a>
    <div class="container mt-5">
        <div class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <div class="card-header" style="background-color: #296D98; color: white; padding: 10px;">
                <h4 class="mb-0 text-center"><strong>Edit Physical Accomplishment</strong></h4>
            </div>
            <div class="card-body p-2">
                <form action="{{ route('projects.updateFifthTab', $physical->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" value="{{ $physical->project_id }}" readonly style="font-size: small;">
                        </div>
                        <div class="col-md-9">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <input type="text" class="form-control border" id="project_name" name="project_name" value="{{ $physical->project_name }}" readonly style="font-size: small;">
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
                                value="{{ $physical->year }}"
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
                                    <option value="{{ $i }}" {{ $physical->quarter == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
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
                            <input type="number" step="0.01" class="form-control border @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ $physical->weight }}" style="font-size: small;">
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
                                value="{{ $physical->actual }}">
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
                                value="{{ $physical->target }}">
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
                                value="{{ $physical->year1 }}"
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
                                    <option value="{{ $i }}" {{ $physical->quarter1 == $i ? 'selected' : '' }}>Quarter {{ $i }}</option>
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
                            <input type="number" step="0.01" class="form-control border @error('weight1') is-invalid @enderror" id="weight1" name="weight1" value="{{ $physical->weight1 }}" style="font-size: small;">
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
                                value="{{ $physical->actual1 }}">
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
                                value="{{ $physical->target1 }}">
                            @error('target1')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>


                        <div class="col-md-2">
                            <label for="outcome_file" class="form-label" style="font-size: small;">
                                <strong>Design Monitoring Framework (DMF):</strong>
                            </label>
                            <input type="file" class="form-control border @error('outcome_file') is-invalid @enderror" id="outcome_file" name="outcome_file" style="font-size: small;" accept=".pdf">
                            <small class="text-muted" style="font-size: x-small;">
                                Accepted file type: PDF (Max size: 25MB)
                            </small>
                            @if($physical->outcome_file)
                            <div class="mt-2" style="font-size: small;">
                                Current file: <a href="{{ asset('storage/' . $physical->outcome_file) }}" target="_blank">View DMF File</a>
                                <input type="hidden" name="existing_outcome_file" value="{{ $physical->outcome_file }}">
                            </div>
                            @endif
                            @error('outcome_file')
                            <div class="invalid-feedback" style="font-size: small;">
                                {{ $message }}
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

                        <div class="col-md-12 text-center mt-4">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="fa fa-save"></i> Update Physical Accomplishment
                            </button>
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
