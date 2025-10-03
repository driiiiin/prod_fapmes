<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class referenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->seedFromCSV('references/ref_donors.csv', 'ref_donors', ['donors_code', 'donors_desc']);
        $this->seedFromCSV('references/ref_funds_type.csv', 'ref_funds_type', ['funds_type_code', 'funds_type_desc']);
        $this->seedFromCSV('references/ref_funds.csv', 'ref_funds', ['funds_id', 'funds_code', 'funds_desc']);
        $this->seedFromCSV('references/ref_gph.csv', 'ref_gph', ['gph_code', 'gph_desc']);
        $this->seedFromCSV('references/ref_level1.csv', 'ref_level1', ['level1_code', 'level1_desc']);
        $this->seedFromCSV('references/ref_level2.csv', 'ref_level2', ['level1_code', 'level1_desc', 'level2_code', 'level2_desc']);
        $this->seedFromCSV('references/ref_level3.csv', 'ref_level3', ['level3_code', 'level3_desc']);
        $this->seedFromCSV('references/ref_management.csv', 'ref_management', ['management_code', 'management_desc']);
        $this->seedFromCSV('references/ref_depdev.csv', 'ref_depdev', ['depdev_code', 'depdev_desc']);
        $this->seedFromCSV('references/ref_sector.csv', 'ref_sectors', ['sector_code', 'sector_desc']);
        $this->seedFromCSV('references/ref_userlevel.csv', 'ref_userlevels', ['userlevel_code', 'userlevel_desc']);
        $this->seedFromCSV('references/ref_region.csv', 'ref_region', ['regcode', 'regcode_9', 'nscb_reg_name', 'regabbrev', 'UserLevelID', 'addedby', 'status']);
        $this->seedFromCSV('references/ref_prov.csv', 'ref_prov', ['regcode', 'provcode', 'regcode_9', 'provcode_9', 'provname', 'old_names', 'incomeclass', 'addedby', 'UserLevelID',  'status']);
        $this->seedFromCSV('references/ref_citymun.csv', 'ref_citymun', ['regcode', 'provcode', 'citycode', 'regcode_9', 'provcode_9', 'citycode_9', 'cityname', 'geographic_level', 'old_names', 'cityclass', 'incomeclass', 'addedby', 'UserLevelID', 'status']);
        $this->seedFromCSV('references/ref_site.csv', 'ref_site', ['site_code', 'site_desc']);
        $this->seedFromCSV('references/ref_status.csv', 'ref_status', ['status_code', 'status_desc']);
        $this->seedFromCSV('references/ref_uhc.csv', 'ref_uhc', ['uhc_code', 'uhc_desc']);
        $this->seedFromCSV('references/ref_currency.csv', 'ref_currency', ['currency_code', 'currency_desc']);
        $this->seedFromCSV('references/ref_alignment.csv', 'ref_alignment', ['alignment_code', 'alignment_desc']);
        $this->seedFromCSV('references/ref_health_facility.csv', 'ref_health_facility', ['health_facility_code', 'health_facility_desc']);
        $this->seedFromCSV('references/ref_environmental.csv', 'ref_environmental', ['environmental_code', 'environmental_desc']);



    }
    private function seedFromCSV(string $filePath, string $tableName, array $columns): void
    {
        $path = database_path($filePath);
        if (!file_exists($path)) {
            Log::error("CSV file not found: {$path}");
            return;
        }

        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data); // Skip header if present

        foreach ($data as $row) {
            if (count($row) < count($columns)) {
                Log::warning('Skipping row due to insufficient columns: ', $row);
                continue;
            }

            $insertData = [];
            foreach ($columns as $index => $column) {
                $insertData[$column] = isset($row[$index]) ? $row[$index] : null;
            }

            DB::table($tableName)->insert($insertData);
        }
    }
}

