<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ImplementationSchedule;
use App\Models\Level;
use App\Models\FinancialAccomplishment;
use App\Models\PhysicalAccomplishment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class SectiononeController extends Controller
{
    //FAPSLIST
    public function fapslist()
    {
        if (auth()->user()->userlevel == -1 || auth()->user()->userlevel == 2 || auth()->user()->userlevel == 5 || auth()->user()->userlevel == 6) {
            $projects = Project::orderByDesc('created_at')->get();
        } else {
            $projects = Project::where('encoded_by', '=', DB::table('users')->where('userlevel', 3)->orWhere('userlevel', 4)->value('username'))->get();
        }
        return view('app.sectionone.fapslist.index', compact('projects'));
    }
}
