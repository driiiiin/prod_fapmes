<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <div class="container-fluid mt-4 pt-4" style="width: 95%;">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="text-left mb-0"><strong>Overall Distribution List</strong></h4>
                    <div class="d-flex align-items-center" style="height: 100%;">
                        <a href="{{ route('overall_area_distribution.report') }}" class="btn btn-primary btn-sm d-flex align-items-center justify-content-center" style="height: 38px;">
                            <i class="fa fa-bar-chart"></i> <span class="ms-1">Generate Report</span>
                        </a>
                    </div>
                </div>
                <ul class="nav nav-tabs mt-3" id="myTab" role="tablist" style="font-size: 14px;">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="funding-sources-tab" data-bs-toggle="tab" href="#funding-sources" role="tab" aria-controls="funding-sources" aria-selected="true">Funding Source</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="management-types-tab" data-bs-toggle="tab" href="#management-types" role="tab" aria-controls="management-types" aria-selected="false">Management Type</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="depdev-classes-tab" data-bs-toggle="tab" href="#depdev-classes" role="tab" aria-controls="depdev-classes" aria-selected="false">DePDev Classification</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="gph_implemented-tab" data-bs-toggle="tab" href="#gph_implemented" role="tab" aria-controls="gph_implemented" aria-selected="false">Type of GPH Implemented</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="level1-tab" data-bs-toggle="tab" href="#level1" role="tab" aria-controls="level1" aria-selected="false">Health Area (Level 1)</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="level2-tab" data-bs-toggle="tab" href="#level2" role="tab" aria-controls="level2" aria-selected="false">Health Area (Level 2)</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="level3-tab" data-bs-toggle="tab" href="#level3" role="tab" aria-controls="level3" aria-selected="false">Health Systems Building Blocks</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="type-of-funds-tab" data-bs-toggle="tab" href="#type-of-funds" role="tab" aria-controls="type-of-funds" aria-selected="false">Type of Fund</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="fund-and-management-tab" data-bs-toggle="tab" href="#fund-and-management" role="tab" aria-controls="fund-and-management" aria-selected="false">Fund & Management</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="project-list-data-table-tab" data-bs-toggle="tab" href="#project-list-data-table" role="tab" aria-controls="project-list-data-table" aria-selected="false">Data Table</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content col-md-12" id="myTabContent">
                <div class="tab-pane fade show active" id="funding-sources" role="tabpanel" aria-labelledby="funding-sources-tab">
                    <!-- Content for Funding Sources -->
                @include('app.sectiontwo.overall_area_distribution.funding_sources.tab1')
                </div>
                <div class="tab-pane fade" id="management-types" role="tabpanel" aria-labelledby="management-types-tab">
                    <!-- Content for Management Types -->
                @include('app.sectiontwo.overall_area_distribution.management_types.tab2')
                </div>
                <div class="tab-pane fade" id="depdev-classes" role="tabpanel" aria-labelledby="depdev-classes-tab">
                    <!-- Content for DEPDev -->
                @include('app.sectiontwo.overall_area_distribution.depdev.tab3')
                </div>
                <div class="tab-pane fade" id="gph_implemented" role="tabpanel" aria-labelledby="gph_implemented-tab">
                    <!-- Content for Type of GPH Implemented -->
                @include('app.sectiontwo.overall_area_distribution.gph.tab4')
                </div>
                <div class="tab-pane fade" id="level1" role="tabpanel" aria-labelledby="level1-tab">
                    <!-- Content for Level 1 -->
                @include('app.sectiontwo.overall_area_distribution.level1.tab5')
                </div>
                <div class="tab-pane fade" id="level2" role="tabpanel" aria-labelledby="level2-tab">
                    <!-- Content for Level 2 -->
                @include('app.sectiontwo.overall_area_distribution.level2.tab6')
                </div>
                <div class="tab-pane fade" id="level3" role="tabpanel" aria-labelledby="level3-tab">
                    <!-- Content for Level 3 -->
                @include('app.sectiontwo.overall_area_distribution.level3.tab7')
                </div>
                <div class="tab-pane fade" id="type-of-funds" role="tabpanel" aria-labelledby="type-of-funds-tab">
                    <!-- Content for Type of Funds -->
                @include('app.sectiontwo.overall_area_distribution.type_of_funds.tab8')
                </div>
                <div class="tab-pane fade" id="fund-and-management" role="tabpanel" aria-labelledby="fund-and-management-tab">
                    <!-- Content for Fund & Management -->
                @include('app.sectiontwo.overall_area_distribution.fund_management.tab9')
                </div>
                <div class="tab-pane fade" id="project-list-data-table" role="tabpanel" aria-labelledby="project-list-data-table-tab">
                    <!-- Content for Data Table -->
                @include('app.sectiontwo.overall_area_distribution.data_table.tab10')
                </div>
            </div>
        </div>
    </div>
@endif
</x-app-layout>
