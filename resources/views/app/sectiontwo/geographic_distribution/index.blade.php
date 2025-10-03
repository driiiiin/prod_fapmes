<x-app-layout>
@if (in_array(auth()->user()->userlevel, [-1, 2, 5, 6]))
    <style>
        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }
        .legend {
            line-height: 18px;
            color: #555;
        }
        .legend i {
            width: 18px;
            height: 18px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        .map-error {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1100;
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
            padding: 20px 30px;
            border-radius: 5px;
            font-size: 1.2rem;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            display: none;
        }
    </style>

    <div class="container-fluid mt-4 pt-4" style="width: 90%;">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="text-left mb-0"><strong>Geographic Distribution List</strong></h4>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="project-list-tab" data-bs-toggle="tab" href="#project-list" role="tab"
                           aria-controls="project-list" aria-selected="true">Multiple Projects</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="progress-update-tab" data-bs-toggle="tab" href="#progress-update" role="tab"
                           aria-controls="progress-update" aria-selected="false">Data Table</a>
                    </li>
                </ul>
            </div>

            <div class="tab-content" id="myTabContent">
                <!-- Map Tab -->
                <div class="tab-pane fade show active" id="project-list" role="tabpanel" aria-labelledby="project-list-tab">
                    <div class="card-body pt-0">
                        <!-- Statistics Panel -->
                        <div class="row mb-3">
                            <div class="col-md-12 mt-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row text-center">
                                            @php
                                                // Always get the total number of projects in the table
                                                $totalProjects = \App\Models\Project::count();

                                                // Get per-province results for the table and map
                                                $results = \App\Models\Project::getProjectCountByRegionAndProvince();
                                                $provincesWithProjects = 0;
                                                foreach($results as $row) {
                                                    $projectCount = (int)$row->project_count;
                                                    if ($projectCount > 0) {
                                                        $provincesWithProjects++;
                                                    }
                                                }
                                                $totalProvinces = $results->count();
                                                $coveragePercentage = $totalProvinces > 0 ? round(($provincesWithProjects / $totalProvinces) * 100, 1) : 0;
                                                $avgProjects = $totalProvinces > 0 ? round($totalProjects / $totalProvinces, 1) : 0;
                                            @endphp
                                            <div class="col-md-3">
                                                <h5 class="text-primary mb-1" id="total-projects">{{ $totalProjects }}</h5>
                                                <div class="small text-muted">Total number of projects</div>
                                            </div>
                                            <div class="col-md-3">
                                                <h5 class="text-success mb-1" id="provinces-with-projects">{{ $provincesWithProjects }}</h5>
                                                <small class="text-muted">Provinces with Projects</small>
                                            </div>
                                            <div class="col-md-3">
                                                <h5 class="text-info mb-1" id="coverage-percentage">{{ $coveragePercentage }}%</h5>
                                                <small class="text-muted">Coverage</small>
                                            </div>
                                            <div class="col-md-3">
                                                <h5 class="text-warning mb-1" id="avg-projects">{{ $avgProjects }}</h5>
                                                <small class="text-muted">Avg Projects/Province</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end mb-2">
                                    <button type="button" class="btn btn-primary btn-sm" onclick="refreshMapData()">
                                        <i class="fas fa-sync-alt"></i> Refresh Map Data
                                    </button>
                                </div>
                                <div id="map" style="height: 800px; z-index: 0; position: relative;">
                                    <div class="loading" id="loading">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="ms-2">Loading map data...</span>
                                    </div>
                                    <div class="map-error" id="map-error">
                                        <strong>Error loading map data.</strong><br>
                                        Please try again later or contact the administrator if the problem persists.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table Tab -->
                <div class="tab-pane fade" id="progress-update" role="tabpanel" aria-labelledby="progress-update-tab">
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table id="geographic-distribution-table" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="background-color: #296D98; color: white;" class="text-center">No.</th>
                                        <th style="background-color: #296D98; color: white;" class="text-center">Region</th>
                                        <th style="background-color: #296D98; color: white;" class="text-center">Province</th>
                                        <th style="background-color: #296D98; color: white;" class="text-center">Project Count</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $results = \App\Models\Project::getProjectCountByRegionAndProvince();
                                    @endphp
                                    @foreach($results as $key => $row)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $row->region_name }}</td>
                                            <td class="text-center">{{ $row->provname }}</td>
                                            <td class="text-center">{{ $row->project_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaflet CSS & JS -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"  /> -->
    <!-- <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script> -->

    <!-- jQuery -->
    <!-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> -->

    <!-- Bootstrap Bundle -->
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script> -->

    <!-- DataTables -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.bootstrap5.css"  />
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.bootstrap5.js"></script> -->

    <!-- Main Script -->
    <script>
        // Global variables
        var map, geojsonLayer, info, legend;
        var projectData = {};
        var maxCount = 0;

        // Helper to normalize province names
        function normalizeProvinceName(name) {
            return name ? name.trim().toUpperCase() : '';
        }

        // Initialize the map
        function initMap() {
            map = L.map('map').setView([12.8797, 121.7740], 6);

            // Set max bounds to the Philippines
            var bounds = L.latLngBounds(
                [4.5, 116.5],   // Southwest
                [21.5, 127.0]   // Northeast
            );
            map.setMaxBounds(bounds);

            // Use OpenStreetMap as the basemap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 18
            }).addTo(map);

            // Create info control
            createInfoControl();

            // Create legend control
            createLegendControl();
        }

        // Function to get color based on project count (strong color for 1+ projects)
        function getColor(count) {
            var bins = [1, 3, 5, 8, 11];
            var colors = ['#feb24c', '#fd8d3c', '#fc4e2a', '#e31a1c', '#bd0026'];
            if (count === 0) return 'none'; // transparent for 0
            for (var i = bins.length - 1; i >= 0; i--) {
                if (count >= bins[i]) return colors[i];
            }
            return colors[0];
        }

        // Function to get radius based on project count
        function getRadius(count) {
            if (count === 0) return 3;
            return Math.max(5, Math.min(20, 5 + (count * 2)));
        }

        // Create info control
        function createInfoControl() {
            info = L.control();

            info.onAdd = function(map) {
                this._div = L.DomUtil.create('div', 'info');
                this.update();
                return this._div;
            };

            info.update = function(props) {
                if (props) {
                    var provinceName = normalizeProvinceName(props.NAME_2);
                    var count = projectData[provinceName] || 0;
                    this._div.innerHTML = '<h4>Project Distribution</h4>' +
                        '<b>' + props.NAME_2 + '</b><br />' +
                        'Projects: ' + count;
                } else {
                    this._div.innerHTML = '<h4>Project Distribution</h4>' +
                        'Hover over a province';
                }
            };

            info.addTo(map);
        }

        // Create legend control (strong color for 1+ projects, no 0 in legend)
        function createLegendControl() {
            legend = L.control({position: 'bottomright'});
            legend.onAdd = function(map) {
                var div = L.DomUtil.create('div', 'info legend');
                var bins = [1, 3, 5, 8, 11];
                var colors = ['#feb24c', '#fd8d3c', '#fc4e2a', '#e31a1c', '#bd0026'];
                div.innerHTML = '<b>Project Count</b><br>';
                for (var i = 0; i < bins.length; i++) {
                    var from = bins[i];
                    var to = bins[i + 1] ? bins[i + 1] - 1 : '+';
                    div.innerHTML +=
                        '<i style="background:' + colors[i] + '"></i> ' +
                        from + (to !== '+' ? '&ndash;' + to + '<br>' : '+');
                }
                return div;
            };
            legend.addTo(map);
        }

        // Load and process data
        function loadMapData() {
            // Show loading indicator
            $('#loading').show();
            $('#map-error').hide();

            // Load GeoJSON data which now includes all project data
            loadGeoJSONData();
        }

        // Load GeoJSON data from our real-time endpoint
        function loadGeoJSONData() {
            $('#loading').show();
            $('#map-error').hide();

            $.ajax({
                url: "/geographic_distribution/geojson",
                method: 'GET',
                dataType: 'json',
                success: function(geojsonData) {
                    $('#map-error').hide();
                    if (geojsonLayer) {
                        map.removeLayer(geojsonLayer);
                    }

                    // Extract project data for info/legend
                    projectData = {};
                    maxCount = 0;
                    geojsonData.features.forEach(function(feature) {
                        var provinceName = normalizeProvinceName(feature.properties.NAME_2);
                        var count = feature.properties.project_count || 0;
                        projectData[provinceName] = count;
                        if (count > maxCount) maxCount = count;
                    });

                    // Remove and recreate legend for dynamic bins
                    if (legend) {
                        legend.remove();
                    }
                    createLegendControl();

                    // Draw polygons with color
                    geojsonLayer = L.geoJSON(geojsonData, {
                        style: function(feature) {
                            var count = feature.properties.project_count || 0;
                            var fill = getColor(count);
                            return {
                                fillColor: fill,
                                weight: count > 0 ? 2 : 1, // thicker border if has project
                                opacity: 1,
                                color: count > 0 ? '#444' : '#888', // darker border if has project
                                fillOpacity: fill === 'none' ? 0 : 0.7
                            };
                        },
                        onEachFeature: function(feature, layer) {
                            var count = feature.properties.project_count || 0;
                            layer.bindPopup('<b>' + feature.properties.NAME_2 + '</b><br>Projects: ' + count);
                            layer.on({
                                mouseover: function(e) {
                                    var layer = e.target;
                                    layer.setStyle({
                                        weight: 3,
                                        fillOpacity: 1
                                    });
                                    info.update(feature.properties);
                                },
                                mouseout: function(e) {
                                    geojsonLayer.resetStyle(e.target);
                                    info.update();
                                },
                                click: function(e) {
                                    map.fitBounds(e.target.getBounds());
                                }
                            });
                        }
                    }).addTo(map);

                    $('#loading').hide();
                },
                error: function(xhr, status, error) {
                    $('#loading').hide();
                    $('#map-error').show().html(
                        '<strong>Error loading map data.</strong><br>' +
                        'Please try again later or contact the administrator if the problem persists.'
                    );
                }
            });
        }

        // Function to refresh map data
        function refreshMapData() {
            $('#loading').show();
            loadMapData();
        }

        // Initialize everything when document is ready
        $(document).ready(function() {
            initMap();
            loadMapData();

            // Initialize DataTable
            new DataTable('#geographic-distribution-table', {
                pageLength: 25,
                order: [[3, 'desc']], // Sort by project count descending (column index 3)
                language: {
                    search: "Search provinces:",
                    lengthMenu: "Show _MENU_ provinces per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ provinces"
                }
            });
        });
    </script>
@endif
</x-app-layout>
