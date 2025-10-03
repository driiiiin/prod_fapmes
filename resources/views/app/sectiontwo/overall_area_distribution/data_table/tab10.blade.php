<div class="tab-pane fade show active" id="datatable" role="tabpanel" aria-labelledby="datatable-tab">
    <div class="card-body pt-0">
        <div class="row">
            <div class="col-md-12 pt-4">

                <!-- Data Table -->

                <div class="table-responsive">
                    <div class="table-wrapper">
                        <table class="table table-striped table-bordered" id="datatable-table" style="width: 100%;">
                            <thead style="border-top: 1px solid #ccc;">
                                <tr class="text-center">
                                    <!-- <th class="text-center">Action</th> -->
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Project ID</th>
                                    <th class="text-center">Project Name</th>
                                    <th class="text-center">Short Title</th>
                                    <th class="text-center">Funding Source</th>
                                    <th class="text-center">Project Donors</th>
                                    <th class="text-center">DEPDev</th>
                                    <th class="text-center">GPH</th>
                                    <th class="text-center">Fund Type</th>
                                    <th class="text-center">Fund Management</th>
                                    <th class="text-center">Management</th>
                                    <!-- <th class="text-center">Manager</th>
                                    <th class="text-center">Sector</th>
                                    <th class="text-center">Sites</th>
                                    <th class="text-center">Agreement</th>
                                    <th class="text-center">Site Specific</th>
                                    <th class="text-center">Classification</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Outcome</th> -->
                                    <th class="text-center">Total Budget</th>
                                    <th class="text-center">Budget</th>

                                </tr>
                            </thead>
                            <tbody>
                                @forelse(collect($datatable)->sortByDesc('project_id') as $datatable)
                                <tr class="datatable-row"
                                    data-id="{{ $datatable->id }}"
                                    data-project-id="{{ $datatable->project_id }}"
                                    data-project-name="{{ $datatable->project_name }}"
                                    data-short-title="{{ $datatable->short_title }}"
                                    data-funding-source="{{ $datatable->funding_source }}"
                                    data-donor="{{ $datatable->donor }}"
                                    data-depdev="{{ $datatable->depdev }}"
                                    data-management="{{ $datatable->management }}"
                                    data-gph="{{ $datatable->gph }}"
                                    data-fund-type="{{ $datatable->fund_type }}"
                                    data-fund-management="{{ $datatable->fund_management }}">

                                    <td class="text-center">{{ $datatable->id }}</td>
                                    <td style="min-width: 100px;" class="text-center">{{ $datatable->project_id }}</td>
                                    <td style="min-width: 400px;" class="text-center">{{ $datatable->project_name }}</td>
                                    <td style="min-width: 100px;" class="text-center">{{ $datatable->short_title }}</td>
                                    <td style="min-width: 300px;" class="text-center">{{ $datatable->funding_source }}</td>
                                    <td style="min-width: 100px;" class="text-center">{{ $datatable->donor }}</td>
                                    <td class="text-center">{{ $datatable->depdev }}</td>
                                    <td class="text-center">{{ $datatable->gph }}</td>
                                    <td class="text-center">{{ $datatable->fund_type }}</td>
                                    <td class="text-center">{{ $datatable->fund_management }}</td>
                                    <td class="text-center">{{ $datatable->management }}</td>
                                    <td class="text-center">{{ number_format($datatable->total_budget, 2, '.', ',') }}</td>
                                    <td class="text-center">{{ number_format($datatable->latest_budget, 2, '.', ',') }}</td>
                                </tr>
                                @empty
                                <!-- <tr>
                                    <td colspan="13" class="text-center">No project found.</td>
                                </tr> -->
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <!-- <div class="col-md-3 mt-4">
                @include('app.sectiontwo.overall_area_distribution.filter')
            </div> -->
        </div>
    </div>
</div>
