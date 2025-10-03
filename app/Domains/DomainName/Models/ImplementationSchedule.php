<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImplementationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'id',
        'project_id',
        'project_name',
        'start_date',
        'interim_date',
        'end_date',
        'extension',
        'duration',
        'time_elapsed',
        'p_time_elapsed',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

