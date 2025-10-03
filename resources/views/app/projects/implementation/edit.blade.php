<x-app-layout>
    <a href="{{ route('projects.index') }}" style="float: right; margin-right: 50px; margin-top: 10px; text-decoration: none; color: black; display: flex; align-items: center;">
        <img src="{{ asset('images/direction.png') }}" alt="Back" style="width: 20px; height: 20px; margin-right: 5px;">Back
    </a>
    <div class="container mt-5">
        <div class="container mx-auto mt-6 px-6 py-4 bg-white shadow-md overflow-hidden">
            <div class="card-header" style="background-color: #296D98; color: white; padding: 10px;">
                <h4 class="mb-0 text-center"><strong>Edit Implementation Schedule</strong></h4>
            </div>
            <div class="card-body p-2">
                <form action="{{ route('projects.updateSecondTab', $implementation->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="project_id" class="form-label" style="font-size: small;"><strong>Project ID:</strong></label>
                            <input type="text" class="form-control border" id="project_id" name="project_id" value="{{ $implementation->project_id }}"  readonly style="font-size: small;">
                        </div>
                        <div class="col-md-9">
                            <label for="project_name" class="form-label" style="font-size: small;"><strong>Project Name:</strong></label>
                            <input type="text" class="form-control border" id="project_name" name="project_name" value="{{ $implementation->project_name }}" readonly style="font-size: small;">
                        </div>
                        <div class="col-md-4">
                            <label for="start_date" class="form-label" style="font-size: small;"><strong>Start Date: <span style="color: red;">*</span></strong></label>
                            <input type="date" class="form-control border" id="start_date" name="start_date" value="{{ $implementation->start_date }}" required style="font-size: small;">
                        </div>
                        <!-- <div class="col-md-4">
                            <label for="interim_date" class="form-label" style="font-size: small;"><strong>Interim Date:</strong></label>
                            <input type="date" class="form-control border" id="interim_date" name="interim_date" value="{{ $implementation->interim_date }}"  style="font-size: small;">
                        </div> -->
                        <div class="col-md-4">
                            <label for="end_date" class="form-label" style="font-size: small;"><strong>End Date: <span style="color: red;">*</span></strong></label>
                            <input type="date" class="form-control border" id="end_date" name="end_date" value="{{ $implementation->end_date }}" required style="font-size: small;">
                        </div>
                        <div class="col-md-4">
                            <label for="extension" class="form-label" style="font-size: small;"><strong>Extension Date:</strong></label>
                            <input type="date" class="form-control border" id="extension" name="extension" value="{{ $implementation->extension }}"  style="font-size: small;">
                        </div>
                        <!-- <div class="col-md-4">
                            <label for="duration" class="form-label" style="font-size: small;"><strong>Duration: <span style="color: red;">*</span></strong></label>
                            <input type="number" class="form-control border" id="duration" name="duration" value="{{ $implementation->duration }}" required style="font-size: small;">
                        </div>
                        <div class="col-md-4">
                            <label for="time_elapsed" class="form-label" style="font-size: small;"><strong>Time Elapsed: <span style="color: red;">*</span></strong></label>
                            <input type="number" class="form-control border" id="time_elapsed" name="time_elapsed" value="{{ $implementation->time_elapsed }}" required style="font-size: small;">
                        </div> -->

                    </div>
                    <div class="mt-4 text-center">
                        <button type="submit" class="btn btn-primary">Update Implementation Schedule</button>
                        <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>