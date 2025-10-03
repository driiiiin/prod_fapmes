<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;
    protected $fillable = [
        'project_id',
        'project_name',
        'level1',
        'level2',
        'level3',
        'l_budget',
        'outcome',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}

