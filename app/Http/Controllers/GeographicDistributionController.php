<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ref_prov;
use App\Models\ref_region;
use Illuminate\Support\Facades\DB;

class GeographicDistributionController extends Controller
{
    public function geographicDistributionData()
    {
        // Use the same method as the table to ensure consistency
        $results = Project::getProjectCountByRegionAndProvince();

        $provinceData = [];
        $regionData = [];
        $maxCount = 0;
        $totalProjects = 0;
        $provincesWithProjects = 0;

        foreach ($results as $row) {
            $provinceName = $row->provname;
            $regionName = $row->region_name;
            $projectCount = (int)$row->project_count;

            // Store province data
            $provinceData[$provinceName] = $projectCount;
            $totalProjects += $projectCount;

            // Track provinces with projects
            if ($projectCount > 0) {
                $provincesWithProjects++;
            }

            // Track maximum count for color scaling
            if ($projectCount > $maxCount) {
                $maxCount = $projectCount;
            }

            // Aggregate region data
            if (!isset($regionData[$regionName])) {
                $regionData[$regionName] = 0;
            }
            $regionData[$regionName] += $projectCount;
        }

        // Calculate statistics
        $totalProvinces = count($provinceData);
        $averageProjectsPerProvince = $totalProvinces > 0 ? round($totalProjects / $totalProvinces, 2) : 0;
        $coveragePercentage = $totalProvinces > 0 ? round(($provincesWithProjects / $totalProvinces) * 100, 2) : 0;

        // Get top 5 provinces by project count
        arsort($provinceData);
        $topProvinces = array_slice($provinceData, 0, 5, true);

        // Get top 5 regions by project count
        arsort($regionData);
        $topRegions = array_slice($regionData, 0, 5, true);

        return response()->json([
            'provinces' => $provinceData,
            'regions' => $regionData,
            'statistics' => [
                'maxCount' => $maxCount,
                'totalProvinces' => $totalProvinces,
                'totalProjects' => \App\Models\Project::count(),
                'provincesWithProjects' => $provincesWithProjects,
                'averageProjectsPerProvince' => $averageProjectsPerProvince,
                'coveragePercentage' => $coveragePercentage
            ],
            'topProvinces' => $topProvinces,
            'topRegions' => $topRegions,
            'debug' => [
                'sampleProvinces' => array_slice($provinceData, 0, 5, true),
                'dataCount' => count($results),
                'timestamp' => now()->toISOString()
            ]
        ]);
    }

    public function getProvincesGeoJSON()
    {
        // Use the same method as the map and table to ensure consistency
        $results = Project::getProjectCountByRegionAndProvince();

        $features = [];

        // Define approximate coordinates for each province (simplified for demo)
        $provinceCoordinates = [
            'Metropolitan Manila' => [14.5995, 120.9842],
            'Cebu' => [10.3157, 123.8854],
            'Davao del Sur' => [6.7660, 125.3286],
            'Iloilo' => [10.7203, 122.5621],
            'Pampanga' => [15.0794, 120.6200],
            'Batangas' => [13.7565, 121.0583],
            'Laguna' => [14.1667, 121.2167],
            'Cavite' => [14.4791, 120.8969],
            'Rizal' => [14.6507, 121.1029],
            'Quezon' => [14.6760, 121.0437],
            'Bulacan' => [14.7943, 120.8799],
            'Nueva Ecija' => [15.5786, 120.9826],
            'Tarlac' => [15.4755, 120.5963],
            'Pangasinan' => [15.8949, 120.2863],
            'La Union' => [16.5000, 120.4167],
            'Ilocos Sur' => [17.3333, 120.5000],
            'Ilocos Norte' => [18.2000, 120.5833],
            'Abra' => [17.5833, 120.7500],
            'Benguet' => [16.4167, 120.5833],
            'Ifugao' => [16.8333, 121.1667],
            'Kalinga' => [17.4167, 121.4167],
            'Apayao' => [18.0000, 121.1667],
            'Mountain Province' => [17.0833, 120.9167],
            'Batanes' => [20.4167, 121.9167],
            'Isabela' => [16.7500, 121.7500],
            'Nueva Vizcaya' => [16.5000, 121.1667],
            'Quirino' => [16.2500, 121.5833],
            'Aurora' => [15.5833, 121.5000],
            'Bataan' => [14.6667, 120.4167],
            'Zambales' => [15.0000, 120.1667],
            'Occidental Mindoro' => [12.7500, 120.9167],
            'Oriental Mindoro' => [13.0000, 121.1667],
            'Marinduque' => [13.4167, 121.9167],
            'Romblon' => [12.5833, 122.2500],
            'Palawan' => [9.5000, 118.5000],
            'Albay' => [13.2500, 123.7500],
            'Camarines Norte' => [14.0000, 122.7500],
            'Camarines Sur' => [13.5000, 123.2500],
            'Catanduanes' => [13.7500, 124.2500],
            'Masbate' => [12.2500, 123.5000],
            'Sorsogon' => [12.7500, 124.0000],
            'Biliran' => [11.5833, 124.5000],
            'Leyte' => [10.7500, 124.7500],
            'Southern Leyte' => [10.2500, 125.0000],
            'Eastern Samar' => [11.5000, 125.5000],
            'Northern Samar' => [12.5000, 124.7500],
            'Samar' => [11.7500, 125.0000],
            'Bohol' => [9.7500, 124.2500],
            'Siquijor' => [9.2500, 123.5000],
            'Negros Oriental' => [9.5000, 122.7500],
            'Negros Occidental' => [10.5000, 122.7500],
            'Guimaras' => [10.5833, 122.5833],
            'Aklan' => [11.5000, 122.2500],
            'Antique' => [10.7500, 122.0000],
            'Capiz' => [11.2500, 122.5000],
            'Bukidnon' => [7.7500, 125.0000],
            'Camiguin' => [9.1667, 124.7500],
            'Lanao del Norte' => [8.0000, 124.0000],
            'Misamis Occidental' => [8.5000, 123.7500],
            'Misamis Oriental' => [8.7500, 124.7500],
            'Zamboanga del Norte' => [8.0000, 123.0000],
            'Zamboanga del Sur' => [7.5000, 123.2500],
            'Zamboanga Sibugay' => [7.7500, 122.7500],
            'Davao del Norte' => [7.5000, 125.7500],
            'Davao de Oro' => [7.2500, 126.0000],
            'Davao Occidental' => [6.5000, 125.5000],
            'Davao Oriental' => [6.7500, 126.2500],
            'Cotabato' => [7.0000, 125.0000],
            'South Cotabato' => [6.2500, 125.0000],
            'Sultan Kudarat' => [6.5000, 124.5000],
            'Sarangani' => [5.7500, 125.2500],
            'Agusan del Norte' => [8.7500, 125.5000],
            'Agusan del Sur' => [8.5000, 126.0000],
            'Surigao del Norte' => [9.5000, 125.7500],
            'Surigao del Sur' => [8.7500, 126.2500],
            'Dinagat Islands' => [10.0000, 125.5000],
            'Basilan' => [6.5000, 122.0000],
            'Lanao del Sur' => [7.7500, 124.2500],
            'Maguindanao' => [6.7500, 124.5000],
            'Sulu' => [5.5000, 121.0000],
            'Tawi-Tawi' => [5.0000, 119.7500]
        ];

        foreach ($results as $row) {
            // Normalize province name for matching
            $provname = strtoupper(trim($row->provname));
            $coordinates = $provinceCoordinates[$row->provname] ?? [12.8797, 121.7740]; // Default to Philippines center

            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'NAME_2' => $provname,
                    'region' => $row->region_name,
                    'project_count' => (int)$row->project_count
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => $coordinates
                ]
            ];
        }

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features
        ]);
    }

    // New method for real-time GeoJSON data
    public function getRealTimeGeoJSON()
    {
        // Get project data using the same method as the view
        $results = Project::getProjectCountByRegionAndProvince();

        // Philippine province coordinates (approximate)
        $provinceCoordinates = [
            'ILOCOS NORTE' => [18.2000, 120.5833],
            'ILOCOS SUR' => [17.3333, 120.5000],
            'LA UNION' => [16.5000, 120.4167],
            'PANGASINAN' => [15.8949, 120.2863],
            'BATANES' => [20.4167, 121.9167],
            'CAGAYAN' => [17.7500, 121.7500],
            'ISABELA' => [16.7500, 121.7500],
            'NUEVA VIZCAYA' => [16.5000, 121.1667],
            'QUIRINO' => [16.2500, 121.5833],
            'BATAAN' => [14.6667, 120.4167],
            'BULACAN' => [14.7943, 120.8799],
            'NUEVA ECIJA' => [15.5786, 120.9826],
            'PAMPANGA' => [15.0794, 120.6200],
            'TARLAC' => [15.4755, 120.5963],
            'ZAMBALES' => [15.0000, 120.1667],
            'AURORA' => [15.5833, 121.5000],
            'BATANGAS' => [13.7565, 121.0583],
            'CAVITE' => [14.4791, 120.8969],
            'LAGUNA' => [14.1667, 121.2167],
            'QUEZON' => [14.6760, 121.0437],
            'RIZAL' => [14.6507, 121.1029],
            'MARINDUQUE' => [13.4167, 121.9167],
            'OCCIDENTAL MINDORO' => [12.7500, 120.9167],
            'ORIENTAL MINDORO' => [13.0000, 121.1667],
            'PALAWAN' => [9.5000, 118.5000],
            'ROMBLON' => [12.5833, 122.2500],
            'ALBAY' => [13.2500, 123.7500],
            'CAMARINES NORTE' => [14.0000, 122.7500],
            'CAMARINES SUR' => [13.5000, 123.2500],
            'CATANDUANES' => [13.7500, 124.2500],
            'MASBATE' => [12.2500, 123.5000],
            'SORSOGON' => [12.7500, 124.0000],
            'AKLAN' => [11.5000, 122.2500],
            'ANTIQUE' => [11.0000, 122.0000],
            'CAPIZ' => [11.5000, 122.7500],
            'ILOILO' => [10.7203, 122.5621],
            'NEGROS OCCIDENTAL' => [10.5000, 122.7500],
            'GUIMARAS' => [10.5000, 122.5000],
            'BOHOL' => [9.7500, 124.2500],
            'CEBU' => [10.3157, 123.8854],
            'NEGROS ORIENTAL' => [9.5000, 123.0000],
            'SIQUIJOR' => [9.2500, 123.5000],
            'EASTERN SAMAR' => [11.5000, 125.5000],
            'LEYTE' => [11.0000, 124.7500],
            'NORTHERN SAMAR' => [12.5000, 124.7500],
            'SAMAR' => [11.7500, 125.0000],
            'SOUTHERN LEYTE' => [10.2500, 125.0000],
            'BILIRAN' => [11.5000, 124.5000],
            'ZAMBOANGA DEL NORTE' => [8.5000, 123.0000],
            'ZAMBOANGA DEL SUR' => [7.5000, 123.5000],
            'ZAMBOANGA SIBUGAY' => [7.7500, 122.7500],
            'BUKIDNON' => [8.0000, 125.0000],
            'CAMIGUIN' => [9.2500, 124.7500],
            'LANAO DEL NORTE' => [8.0000, 124.0000],
            'MISAMIS OCCIDENTAL' => [8.2500, 123.7500],
            'MISAMIS ORIENTAL' => [8.5000, 124.5000],
            'DAVAO DEL NORTE' => [7.5000, 125.7500],
            'DAVAO DEL SUR' => [6.7660, 125.3286],
            'DAVAO ORIENTAL' => [7.0000, 126.2500],
            'DAVAO DE ORO' => [7.5000, 126.0000],
            'DAVAO OCCIDENTAL' => [6.5000, 125.5000],
            'COTABATO' => [7.0000, 124.5000],
            'SOUTH COTABATO' => [6.5000, 125.0000],
            'SULTAN KUDARAT' => [6.5000, 124.5000],
            'SARANGANI' => [6.0000, 125.0000],
            'ABRA' => [17.5833, 120.7500],
            'BENGUET' => [16.4167, 120.5833],
            'IFUGAO' => [16.8333, 121.1667],
            'KALINGA' => [17.4167, 121.4167],
            'MOUNTAIN PROVINCE' => [17.0833, 120.9167],
            'APAYAO' => [18.0000, 121.1667],
            'AGUSAN DEL NORTE' => [9.0000, 125.5000],
            'AGUSAN DEL SUR' => [8.5000, 126.0000],
            'SURIGAO DEL NORTE' => [9.7500, 125.5000],
            'SURIGAO DEL SUR' => [8.5000, 126.0000],
            'DINAGAT ISLANDS' => [10.0000, 125.5000],
            'BASILAN' => [6.5000, 122.0000],
            'LANAO DEL SUR' => [7.7500, 124.2500],
            'SULU' => [5.5000, 121.0000],
            'TAWI-TAWI' => [5.0000, 119.7500],
            'MAGUINDANAO DEL NORTE' => [7.0000, 124.0000],
            'MAGUINDANAO DEL SUR' => [6.7500, 124.5000],
            'NEGROS OCCIDENTAL' => [10.5000, 122.7500],
            'NEGROS ORIENTAL' => [9.5000, 123.0000],
            'SIQUIJOR' => [9.2500, 123.5000]
        ];

        // Create a mapping of province names to project counts
        $projectData = [];
        foreach ($results as $row) {
            $provinceName = strtoupper(trim($row->provname));
            $projectData[$provinceName] = (int)$row->project_count;
        }

        // Create new GeoJSON with proper coordinates
        $features = [];
        foreach ($projectData as $provinceName => $projectCount) {
            $coordinates = $provinceCoordinates[$provinceName] ?? [12.8797, 121.7740]; // Default to Philippines center

            // Create a simple polygon around the coordinates
            $lat = $coordinates[0];
            $lng = $coordinates[1];
            $polygon = [
                [$lng - 0.1, $lat - 0.1],
                [$lng + 0.1, $lat - 0.1],
                [$lng + 0.1, $lat + 0.1],
                [$lng - 0.1, $lat + 0.1],
                [$lng - 0.1, $lat - 0.1]
            ];

            $features[] = [
                'type' => 'Feature',
                'properties' => [
                    'NAME_2' => $provinceName,
                    'name' => $provinceName,
                    'project_count' => $projectCount
                ],
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [$polygon]
                ]
            ];
        }

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => $features
        ];

        return response()->json($geojson);
    }

    public function index()
    {
        $projects = Project::getProjectCountByRegionAndProvince();

        // Always get the total number of projects in the table
        $totalProjects = \App\Models\Project::count();
        $provincesWithProjects = $projects->where('project_count', '>', 0)->count();
        $totalProvinces = $projects->count();

        return view('app.sectiontwo.geographic_distribution.index', compact('projects', 'totalProjects', 'provincesWithProjects', 'totalProvinces'));
    }
}
