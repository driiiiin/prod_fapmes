<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LevelController extends Controller
{
    public function getLevel2Options(Request $request)
    {
        $level1 = $request->input('level1');
        // Fetch Level 2 options based on Level 1 selection
        $level2Options = DB::table('ref_level2')->where('level1_desc', $level1)->select('level2_desc')->get();

        return response()->json($level2Options);
    }

    public function getLevel3Options(Request $request)
    {
        $level2 = $request->input('level2');
        // Fetch Level 3 options based on Level 2 selection
        $level3Options = DB::table('ref_level3')->select('level3_desc')->get();

        return response()->json($level3Options);
    }
}
