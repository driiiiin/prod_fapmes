<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable;

class ImplementationSchedule extends Model implements AuditableContract
{
    use HasFactory;
    use Auditable;

    protected $fillable = [
        // 'id',
        'project_id',
        'project_name',
        'start_date',
        'end_date',
        'extension',
        'encoded_by',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getDurationAttribute()
    {
        $startDate = new \DateTime($this->start_date);
        $endDate = new \DateTime($this->end_date);

        if (!empty($this->extension)) {
            $extensionDate = new \DateTime($this->extension);
            return (int)(($extensionDate->diff($startDate)->y * 12) + $extensionDate->diff($startDate)->m);
        }

        return (int)(($endDate->diff($startDate)->y * 12) + $endDate->diff($startDate)->m);
    }


     public function getTimeElapsedAttribute()
    {
        // Always get fresh project data from database to avoid stale data
        $project = Project::where('project_id', $this->project_id)->first();
        $startDate = \Carbon\Carbon::parse($this->start_date);

        if ($project && $project->status === 'Completed' && $project->completed_date) {
            $completedDate = \Carbon\Carbon::parse($project->completed_date);
            // Ensure we're using the completed date for calculation
            return (int)$startDate->diffInMonths($completedDate);
        }

        // If not completed, calculate against current date
        return (int)$startDate->diffInMonths(\Carbon\Carbon::now('Asia/Manila'));
    }

    public function getPTimeElapsedAttribute()
    {
        // Get fresh duration and time_elapsed values
        $timeElapsed = $this->getTimeElapsedAttribute();
        $duration = $this->getDurationAttribute();

        if ($duration > 0) {
            return number_format(($timeElapsed / $duration) * 100, 2);
        }
        return 0;
    }

    // Store the latest ID in session
    public static function storeLatestId($id)
    {
        session(['latest_implementation_id' => $id]);
    }

    // Get the latest ID from session
    public static function getLatestId()
    {
        return session('latest_implementation_id');
    }

    // Override the save method to store the latest ID
    public function save(array $options = [])
    {
        $saved = parent::save($options);
        if ($saved) {
            self::storeLatestId($this->id);
        }
        return $saved;
    }
}

