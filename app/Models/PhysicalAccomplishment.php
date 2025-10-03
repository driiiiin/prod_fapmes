<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class PhysicalAccomplishment extends Model implements AuditableContract
{
    use HasFactory, Auditable;
    protected $fillable = [
        'project_id',
        'project_name',
        'project_type',
        'year',
        'quarter',
        'weight',
        'actual',
        'target',
        'project_type1',
        'year1',
        'quarter1',
        'weight1',
        'actual1',
        'target1',
        'overall_accomplishment',
        'overall_target',
        'slippage',
        'remarks',
        'slippage_end_of_quarter',
        'outcome_file',
        'encoded_by',
    ];



    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function setOutcomeFileAttribute($value)
    {
        if ($value instanceof \Illuminate\Http\UploadedFile) {
            // Delete old file if it exists
            if ($this->outcome_file && Storage::exists('public/' . $this->outcome_file)) {
                Storage::delete('public/' . $this->outcome_file);
            }
            // Store the new file and save the path
            $this->attributes['outcome_file'] = $value->store('outcomes', 'public');
        } else {
            // If no new file is uploaded, keep the existing file path
            $this->attributes['outcome_file'] = $value;
        }
    }

    public function getOutcomeFileUrlAttribute()
    {
        return $this->outcome_file ? Storage::url($this->outcome_file) : null;
    }

    // public function getOverallTargetAttribute()
    // {
    //     // Get the specific project
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project || !$this->weight || !$this->target) {
    //         return 0;
    //     }

    //     if ($this->project_type === 'Infrastructure') {
    //         return ($this->weight * $this->target) / 100;
    //     } else {
    //         return $this->target;
    //     }
    // }

    // public function getSlippageAttribute()
    // {
    //     // Get the specific project
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     return ($this->actual - $this->target) . '%';
    // }

    // public function getSlippageAttribute()
    // {
    //     // Get the specific project
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     if ($this->target == 0) {
    //         return 0;
    //     }

    //     return intval(($this->actual / $this->target) * 100) . '%';
    // }

    // public function getSlippageEndofQuarterAttribute()
    // {
    //     // Get the specific project
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     return ($this->actual - $this->target_end_of_quarter) . '%';
    // }

    // public function getSlippageEndofQuarterAttribute()
    // {
    //     // Get the specific project
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     if ($this->target_end_of_quarter == 0) {
    //         return 0;
    //     }

    //     return ($this->actual / $this->target_end_of_quarter * 100) . '%';
    // }

    // public function getRemarksAttribute()
    // {
    //     // Get the specific project
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 'No Project Found';
    //     }

    //     $slippage = floatval(str_replace('%', '', $this->getSlippageAttribute()));

    //     if ($slippage < 0) {
    //         return 'BEHIND';
    //     } elseif ($slippage > 0) {
    //         return 'AHEAD';
    //     } else {
    //         return 'ON-TIME';
    //     }
    // }

    // public function getRemarksAttribute()
    // {
    //     // Get the specific project
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 'No Project Found';
    //     }

    //     $slippage = intval(str_replace('%', '', $this->getSlippageAttribute()));

    //     if ($slippage < 100) {
    //         return 'BEHIND';
    //     } elseif ($slippage > 100) {
    //         return 'AHEAD';
    //     } else {
    //         return 'ON-TIME';
    //     }
    // }

    // public function getOverallAccomplishmentAttribute()
    // {
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     if (!$this->weight || !$this->actual || !$this->weight1 || !$this->actual1) {
    //         return 0;
    //     }

    //     return ($this->weight/100 * $this->actual) + ($this->weight1/100 * $this->actual1);
    // }

    // public function getOverallTargetAttribute()
    // {
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     if (!$this->weight || !$this->target || !$this->weight1 || !$this->target1) {
    //         return 0;
    //     }

    //     return ($this->weight/100 * $this->target) + ($this->weight1/100 * $this->target1);

    // }

    // public function getSlippageAttribute()
    // {
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     return ($this->overall_accomplishment - $this->overall_target);
    // }

    // public function getSlippageEndofQuarterAttribute()
    // {
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 0;
    //     }

    //     return (100 - $this->overall_accomplishment);
    // }

    // public function getRemarksAttribute()
    // {
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 'No Project Found';
    //     }

    //     $slippage = floatval(str_replace('%', '', $this->getSlippageAttribute()));

    //     if ($slippage < 0) {
    //         return 'BEHIND';
    //     } elseif ($slippage > 0) {
    //         return 'AHEAD';
    //     } else {
    //         return 'ON-TIME';
    //     }
    // }
    // public function getRemarksAttribute()
    // {
    //     $project = Project::where('project_id', $this->project_id)->first();

    //     if (!$project) {
    //         return 'No Project Found';
    //     }

    //     $slippage = floatval(str_replace('%', '', $this->getSlippageAttribute()));

    //     if ($this->year === 'N/A' || !$this->year)  {
    //         return 'FOR VERIFICATION YEAR';
    //     } elseif ($this->target == 0 || $this->actual == 0 || $this->target1 == 0 || $this->actual1 == 0) {
    //         return 'FOR VERIFICATION TARGET OR ACTUAL';
    //     } elseif ($slippage < 0) {
    //         return 'BEHIND';
    //     } elseif ($slippage > 0) {
    //         return 'AHEAD';
    //     } else {
    //         return 'ON-TIME';
    //     }
    // }

    // In App\Models\PhysicalAccomplishment

public static function getLatestStatsByYear($year = null)
{
    $query = self::query()
        ->join('projects', 'physical_accomplishments.project_id', '=', 'projects.project_id')
        ->whereRaw('physical_accomplishments.created_at = (
            SELECT MAX(created_at)
            FROM physical_accomplishments pa2
            WHERE pa2.project_id = physical_accomplishments.project_id
        )');

    if ($year) {
        $query->whereYear('projects.completed_date', $year);
    }

    // Get all latest records that match criteria
    $results = $query->get([
        'physical_accomplishments.project_id',
        'physical_accomplishments.weight',
        'physical_accomplishments.weight1',
        'physical_accomplishments.remarks'
    ]);

    // Initialize counters
    $stats = [
        'infra' => ['on_time' => 0, 'ahead' => 0, 'behind' => 0],
        'non_infra' => ['on_time' => 0, 'ahead' => 0, 'behind' => 0],
        'combined' => ['on_time' => 0, 'ahead' => 0, 'behind' => 0],
        'total' => ['infra' => 0, 'non_infra' => 0, 'combined' => 0]
    ];

    foreach ($results as $record) {
        if ($record->weight == 100 && $record->weight1 != 100) {
            // Infrastructure-only
            $stats['total']['infra']++;
            switch ($record->remarks) {
                case 'ON-TIME': $stats['infra']['on_time']++; break;
                case 'AHEAD': $stats['infra']['ahead']++; break;
                case 'BEHIND': $stats['infra']['behind']++; break;
            }
        } elseif ($record->weight1 == 100 && $record->weight != 100) {
            // Non-infrastructure-only
            $stats['total']['non_infra']++;
            switch ($record->remarks) {
                case 'ON-TIME': $stats['non_infra']['on_time']++; break;
                case 'AHEAD': $stats['non_infra']['ahead']++; break;
                case 'BEHIND': $stats['non_infra']['behind']++; break;
            }
        } else {
            // Combined
            $stats['total']['combined']++;
            switch ($record->remarks) {
                case 'ON-TIME': $stats['combined']['on_time']++; break;
                case 'AHEAD': $stats['combined']['ahead']++; break;
                case 'BEHIND': $stats['combined']['behind']++; break;
            }
        }
    }

    return $stats;
}

}
