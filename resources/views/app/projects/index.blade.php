<x-app-layout>

    <div class="container-fluid mt-4 pt-4" style="width: 90%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Tabs Navigation -->
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="details-tab" data-bs-toggle="tab" href="#details" role="tab" aria-controls="details" aria-selected="true">Project Details</a>
                    </li>
                    @if(auth()->user()->userlevel != 6)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="implementation-tab" data-bs-toggle="tab" href="#implementation" role="tab" aria-controls="implementation" aria-selected="false">Implementation Schedule</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="levels-tab" data-bs-toggle="tab" href="#levels" role="tab" aria-controls="levels" aria-selected="false">Health Areas</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="financial-tab" data-bs-toggle="tab" href="#financial" role="tab" aria-controls="financial" aria-selected="false">Financial Accomplishments</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="physical-tab" data-bs-toggle="tab" href="#physical" role="tab" aria-controls="physical" aria-selected="false">Physical Accomplishments</a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="card-body pt-0">
                <div class="tab-content" id="myTabContent">
                    <!-- Project Details Tab -->
                    @include('app.projects.details.details')

                    <!-- Implementation Schedule Tab -->
                    @include('app.projects.implementation.implementation')

                    <!-- Levels Tab -->
                    @include('app.projects.level.level')

                    <!-- Financial Accomplishments Tab -->
                    @include('app.projects.financial.financial')

                    <!-- Physical Accomplishments Tab -->
                    @include('app.projects.physical.physical')
                </div>
            </div>
        </div>
    </div>


    <script>
        @if(session('success'))
        Swal.fire({
            title: '<span style="font-size: medium;">{{ session("success") }}</span>',
            icon: 'success',
            confirmButtonColor: '#3085d6',
            confirmButtonText: '<span style="font-size: smaller;">OK</span>'
        });
        @endif
    </script>
</x-app-layout>