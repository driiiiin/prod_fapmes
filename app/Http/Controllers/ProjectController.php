<?php

namespace App\Http\Controllers;

use App\Models\Project; // Make sure to import your Project model
use App\Models\ImplementationSchedule;
use App\Models\Level;
use App\Models\FinancialAccomplishment;
use App\Models\PhysicalAccomplishment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\Echo_;
use DateTime;
use Carbon\Carbon;

use function Laravel\Prompts\error;
use function PHPUnit\Framework\isNull;

class ProjectController extends Controller
{
    // Display a listing of the projects
    public function index()
    {
        $user = auth()->user();

        if (in_array($user->userlevel, [-1, 2, 5, 6])) {
            // Admin/privileged users - get all records
            $projects = Project::all();
            $implementations = ImplementationSchedule::all();
            $levels = Level::all();
            $financials = FinancialAccomplishment::all();
            $physicals = PhysicalAccomplishment::all();
        } else {
            // Regular users - get only their own records or specific userlevel records
            $targetUserlevel = ($user->userlevel == 3) ? 3 : 4;

            $projects = Project::join('users', 'projects.encoded_by', '=', 'users.username')
                ->where('users.userlevel', $targetUserlevel)
                ->select('projects.*')
                ->get();

            $implementations = ImplementationSchedule::join('users', 'implementation_schedules.encoded_by', '=', 'users.username')
                ->where('users.userlevel', $targetUserlevel)
                ->select('implementation_schedules.*')
                ->get();

            $levels = Level::join('users', 'levels.encoded_by', '=', 'users.username')
                ->where('users.userlevel', $targetUserlevel)
                ->select('levels.*')
                ->get();

            $financials = FinancialAccomplishment::join('users', 'financial_accomplishments.encoded_by', '=', 'users.username')
                ->where('users.userlevel', $targetUserlevel)
                ->select('financial_accomplishments.*')
                ->get();

            $physicals = PhysicalAccomplishment::join('users', 'physical_accomplishments.encoded_by', '=', 'users.username')
                ->where('users.userlevel', $targetUserlevel)
                ->select('physical_accomplishments.*')
                ->get();
        }

        return view('app.projects.index', compact('projects', 'implementations', 'levels', 'financials', 'physicals')); // Return the view with projects and implementations
    }

    public function create()

    {
        // You can return a view if needed, but in this case, the form is in the index view
    }


    // Display the specified project
    public function show($id)
    {
        $project = Project::findOrFail($id); // Find the project by ID
        $implementations = ImplementationSchedule::where('project_id', '=', $project->project_id)->get(); // Fetch implementations related to the project
        $levels = Level::where('project_id', '=', $project->project_id)->get();
        $financials = FinancialAccomplishment::where('project_id', '=', $project->project_id)->get();
        $physicals = PhysicalAccomplishment::where('project_id', '=', $project->project_id)->get();

        return view('app.projects.details.show', compact('project', 'implementations', 'levels', 'financials', 'physicals')); // Return the show view with project and implementations data
    }

    // Store a newly created project in storage
    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|string|unique:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/',
            'project_name' => 'required|string|max:255',
            'short_title' => 'required|string|max:100',
            'funding_source' => 'required|string|max:255',
            // 'donor' => 'nullable|string|max:255',
            'depdev' => 'nullable|string|max:255',
            'management' => 'nullable|string|max:255',
            'gph' => 'nullable|string|max:255',
            'fund_type' => 'required|string|max:255',
            'desk_officer' => 'required|nullable|string|max:255',
            'alignment' => 'nullable|array|max:255',
            'environmental' => 'nullable|string',
            'health_facility' => 'nullable|array|max:255',
            'development_objectives' => 'required|nullable|string',
            'sector' => 'nullable|array|max:255',
            // 'sector_others' => 'nullable|string|max:255',
            'sites' => 'required|string|max:255',
            // 'agreement' => 'nullable|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,png', // Increased to 25MB
            'agreement' => 'nullable|file|max:25600|mimes:pdf', // Increased to 25MB
            'site_specific_reg' => 'nullable|array', //change array to string if needed
            'site_specific_prov' => 'nullable|array', //change array to string if needed
            'site_specific_city' => 'nullable|array', //change array to string if needed
            'classification' => 'nullable|string|max:255',
            // 'status' => 'nullable|string|max:255',
            // 'uhc' => 'nullable|string|max:255',
            // 'uhc_is' => 'nullable|boolean',
            'outcome' => 'nullable|string',
        ]);

        $request->merge([
            'encoded_by' => auth()->user()->username,
            'donor' => DB::table('ref_funds')->where('funds_desc', $request->input('funding_source'))->value('funds_code'),
        ]);

        // Handle file upload for the agreement
        if ($request->hasFile('agreement')) {
            $filePath = $request->file('agreement')->store('agreements', 'public'); // Store the file and get the path
            $request->merge(['agreement' => $filePath]); // Update the request with the file path
        }

        // Convert array into string for 'site_specific'
        if (isset($request->site_specific_reg)) {
            $regions = DB::table('ref_region')->select('nscb_reg_name')
                ->whereIn('regcode', $request->site_specific_reg)
                ->get()
                ->pluck('nscb_reg_name')
                ->toArray();
            $request->merge([
                'site_specific_reg' => implode(', ', $regions),
            ]);
        }

        if (isset($request->site_specific_prov)) {
            $provinces = DB::table('ref_prov')->select('provname')
                ->whereIn('provcode', $request->site_specific_prov)
                ->get()
                ->pluck('provname')
                ->toArray();
            $request->merge([
                'site_specific_prov' => implode(', ', $provinces),
            ]);
        }

        if (isset($request->site_specific_city)) {
            $request->merge([
                'site_specific_city' => implode(', ', $request->site_specific_city),
            ]);
        }

        // Convert sector array to comma-separated string, or set to 'N/A' if not present
        if (isset($request->sector)) {
            $request->merge([
                'sector' => implode(', ', $request->sector),
            ]);
        }
        if (!$request->has('sector')) {
            $request->merge(['sector' => 'N/A']);
        }

        // Convert alignment array to comma-separated string, or set to 'N/A' if not present
        if (isset($request->alignment)) {
            $request->merge([
                'alignment' => implode(', ', $request->alignment),
            ]);
        }
        if (!$request->has('alignment')) {
            $request->merge(['alignment' => 'N/A']);
        }

        // Convert health_facility array to comma-separated string, or set to 'N/A' if not present
        if (isset($request->health_facility)) {
            $request->merge([
                'health_facility' => implode(', ', $request->health_facility),
            ]);
        }
        if (!$request->has('health_facility')) {
            $request->merge(['health_facility' => 'N/A']);
        }

        // Prepare the data for the project
        $request->merge([
            'fund_management' => $request->input('management') . ', ' . $request->input('fund_type'),
        ]);

        // Handle file upload for the agreement file
        if ($request->hasFile('agreement')) {
            $filePath = $request->file('agreement')->store('agreements', 'public');
            $request->merge(['agreement' => $filePath]);
        }

        // Create a new project
        Project::create($request->all());

        return redirect()->route('projects.index')->with('success', 'Project added successfully.');
    }

    public function storeSecondTab(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table', // Ensure each project_id exists in the projects table
            'project_name' => 'required|string|exists:projects,project_name',
            'start_date' => 'required|date',
            // 'interim_date' => 'nullable|date|after:start_date',
            'end_date' => 'required|date|after:start_date',
            'extension' => 'nullable|date|after:end_date',
            // 'duration' => 'required|numeric|min:0',
            // 'time_elapsed' => 'required|numeric|min:0',

        ]);

        $request->merge([
            'encoded_by' => auth()->user()->username
        ]);

        // Create the implementation schedule
        ImplementationSchedule::create([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'extension' => $request->input('extension'),
            // 'duration' => $request->input('duration'),
            // 'time_elapsed' => $request->input('time_elapsed'),
            // 'p_time_elapsed' => $p_time_elapsed,
            'encoded_by' => $request->input('encoded_by'),
        ]);

        // Redirect back to the projects index with a success message
        return redirect()->route('projects.index')->with('success', 'Implementation Schedule added successfully.');
    }


    // Store the specified project's implementation schedule in storage
    public function storeThirdTab(Request $request)
    {
        // Validate the request data
        $request->validate([
            'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table
            'project_name' => 'required|string|exists:projects,project_name',
            'level1' => 'nullable|string|max:255',
            'level2' => 'nullable|string|max:255',
            'level3' => 'required|string|max:255',
            'l_budget' => 'required|numeric|min:0',
            'outcome' => 'nullable|string|max:255',
        ]);

        $request->merge([
            'encoded_by' => auth()->user()->username
        ]);

        // Create the level
        Level::create([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'level1' => $request->input('level1') ?: 'N/A',
            'level2' => $request->input('level2') ?: 'N/A',
            'level3' => $request->input('level3'),
            'l_budget' => $request->input('l_budget'),
            'outcome' => $request->input('outcome'),
            'encoded_by' => $request->input('encoded_by'),
        ]);

        // Redirect back to the projects index with a success message
        return redirect()->route('projects.index')->with('success', 'Health Area added successfully.');
    }


    // Store the specified project's financial accomplishments in storage
    public function storeFourthTab(Request $request)
    {
        // Validate the request data
        $request->validate([
            'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table
            'project_name' => 'required|string|exists:projects,project_name',
            'orig_budget' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:255',
            'rate' => 'required|numeric|min:0',
            'budget' => 'required|numeric|min:0',
            'lp' => 'nullable|numeric|min:0',
            'gp' => 'nullable|numeric|min:0',
            'gph_counterpart' => 'nullable|numeric|min:0',
            'disbursement' => 'required|numeric|min:0',
        ]);

        $request->merge([
            'encoded_by' => auth()->user()->username
        ]);

        // Calculate percentage of time elapsed
        $p_disbursement = 0; // Default value
        if ($request->input('budget') > 0) {
            $p_disbursement = number_format(($request->input('disbursement') / $request->input('budget')) * 100, 2);
        }
        // Create the financial accomplishment
        FinancialAccomplishment::create([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'orig_budget' => $request->input('orig_budget'),
            'currency' => $request->input('currency'),
            'rate' => $request->input('rate'),
            'budget' => $request->input('budget'),
            'lp' => $request->input('lp'),
            'gp' => $request->input('gp'),
            'gph_counterpart' => $request->input('gph_counterpart'),
            'disbursement' => $request->input('disbursement'),
            'p_disbursement' => $p_disbursement,
            'encoded_by' => $request->input('encoded_by'),
        ]);

        // Redirect back to the projects index with a success message
        return redirect()->route('projects.index')->with('success', 'Financial Accomplishment added successfully.');
    }


    // Store the specified project's physical accomplishments in storage
    public function storeFifthTab(Request $request)
    {
        // Validate the request data
        $request->validate([
            'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table
            'project_name' => 'required|string|exists:projects,project_name',
            'project_type' => 'nullable|string',
            'year' => 'nullable|string',
            'quarter' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0|max:100',
            'actual' => 'nullable|numeric|min:0',
            'target' => 'nullable|numeric|min:0',
            'project_type1' => 'nullable|string',
            'year1' => 'nullable|string',
            'quarter1' => 'nullable|string',
            'weight1' => 'nullable|numeric|min:0|max:100',
            'actual1' => 'nullable|numeric|min:0',
            'target1' => 'nullable|numeric|min:0',
            'outcome_file' => 'nullable|file|max:25600|mimes:pdf',
        ]);

        // Get weight inputs and set defaults to 0 if null
        $weight = $request->input('weight', 0);
        $weight1 = $request->input('weight1', 0);

        // If both weights are 0, return error
        if ($weight == 0 && $weight1 == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'At least one weight must be provided');
        }

        // Validate that if weight is provided, quarter/actual/target are also provided
        if ($weight > 0) {
            if (!$request->filled('quarter') || !$request->filled('actual') || !$request->filled('target')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'When weight is provided, quarter, actual and target must also be provided');
            }
        }

        // Validate that if weight1 is provided, quarter1/actual1/target1 are also provided
        if ($weight1 > 0) {
            if (!$request->filled('quarter1') || !$request->filled('actual1') || !$request->filled('target1')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'When weight1 is provided, quarter1, actual1 and target1 must also be provided');
            }
        }

        // If only one weight is provided, set the other to complete 100
        if ($weight == 0) {
            // Check if weight1 equals 100, otherwise return error
            if ($weight1 != 100) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Weight1 must equal 100');
            }
        } elseif ($weight1 == 0) {
            if ($weight != 100) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Weight must equal 100');
            }
        }
        // If both weights are provided but don't sum to 100
        elseif ($request->input('weight') + $request->input('weight1') != 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The sum of weight and weight1 must equal to 100');
        }

        $request->merge([
            'encoded_by' => auth()->user()->username,
            'weight' => $weight,
            'weight1' => $weight1
        ]);

        // Handle file upload for the outcome file
        if ($request->hasFile('outcome_file')) {
            $filePath = $request->file('outcome_file')->store('outcomes', 'public');
            $request->merge(['outcome_file' => $filePath]);
        }

        // Set default values to 0 if any of the physical accomplishment fields are null
        foreach (['actual', 'target', 'actual1', 'target1'] as $field) {
            if (is_null($request->input($field))) {
                $request->merge([$field => 0]);
            }
        }

        $slippage = (($weight/100 * $request->input('actual')) + ($weight1/100 * $request->input('actual1'))) - (($weight/100 * $request->input('target')) + ($weight1/100 * $request->input('target1')));
        $overall_target = ($weight/100 * $request->input('target')) + ($weight1/100 * $request->input('target1'));

        // Create the physical accomplishment
        PhysicalAccomplishment::create([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'project_type' => $request->input('project_type'),
            'year' => $request->input('year'),
            'quarter' => $request->input('quarter'),
            'weight' => $weight,
            'actual' => $request->input('actual'),
            'target' => $request->input('target'),

            'project_type1' => $request->input('project_type1'),
            'year1' => $request->input('year1'),
            'quarter1' => $request->input('quarter1'),
            'weight1' => $weight1,
            'actual1' => $request->input('actual1'),
            'target1' => $request->input('target1'),

            'overall_accomplishment' => ($weight/100 * $request->input('actual')) + ($weight1/100 * $request->input('actual1')),
            'overall_target' => ($weight/100 * $request->input('target')) + ($weight1/100 * $request->input('target1')),
            'slippage' => $slippage,
            'remarks' => $slippage < 0 ? 'BEHIND' : ($slippage > 0 ? 'AHEAD' : 'ON-TIME'),
            'slippage_end_of_quarter' => 100 - $slippage - $overall_target,


            'outcome_file' => $request->input('outcome_file'),
            'encoded_by' => $request->input('encoded_by'),
        ]);

        return redirect()->route('projects.index')->with('success', 'Physical Accomplishment added successfully.');
    }

    // Show the form for editing the specified project
    public function edit($id)
    {
        $project = Project::findOrFail($id); // Find the project by ID

        // Convert the selected values to arrays if they are stored as strings
        $selectedRegions = is_string($project->site_specific_reg) ? explode(',', $project->site_specific_reg) : (array) $project->site_specific_reg;
        $selectedProvinces = is_string($project->site_specific_prov) ? explode(',', $project->site_specific_prov) : (array) $project->site_specific_prov;
        $selectedCities = is_string($project->site_specific_city) ? explode(',', $project->site_specific_city) : (array) $project->site_specific_city;

        return view('app.projects.details.edit', compact('project', 'selectedRegions', 'selectedProvinces', 'selectedCities')); // Return the edit view with project data and selected values
    }
    public function editSecondTab($id)
    {
        // Fetch the implementation schedule by ID
        $implementation = ImplementationSchedule::findOrFail($id);

        // Pass the implementation variable to the view
        return view('app.projects.implementation.edit', compact('implementation'));
    }
    public function editThirdTab($id)
    {
        // Fetch the level by ID
        $level = Level::findOrFail($id);

        // Pass the level variable to the view
        return view('app.projects.level.edit', compact('level'));
    }

    public function editFourthTab($id)
    {
        // Fetch the financial accomplishment by ID
        $financial = FinancialAccomplishment::findOrFail($id);

        // Pass the financial variable to the view
        return view('app.projects.financial.edit', compact('financial'));
    }

    public function editFifthTab($id)
    {
        // Fetch the physical accomplishment by ID
        $physical = PhysicalAccomplishment::findOrFail($id);

        // Pass the physical variable to the view
        return view('app.projects.physical.edit', compact('physical'));
    }

    // Update the specified project's physical accomplishments in storage
    public function updateFifthTab(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table
            'project_name' => 'required|string|exists:projects,project_name',
            'project_type' => 'nullable|string',
            'year' => 'nullable|string',
            'quarter' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0|max:100',
            'actual' => 'nullable|numeric|min:0',
            'target' => 'nullable|numeric|min:0',
            'project_type1' => 'nullable|string',
            'year1' => 'nullable|string',
            'quarter1' => 'nullable|string',
            'weight1' => 'nullable|numeric|min:0|max:100',
            'actual1' => 'nullable|numeric|min:0',
            'target1' => 'nullable|numeric|min:0',
            'outcome_file' => 'nullable|file|max:25600|mimes:pdf',
        ]);

        // Get weight inputs and set defaults to 0 if null
        $weight = $request->input('weight', 0);
        $weight1 = $request->input('weight1', 0);

        // If both weights are 0, return error
        if ($weight == 0 && $weight1 == 0) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'At least one weight must be provided');
        }

        // Validate that if weight is provided, quarter/actual/target are also provided
        if ($weight > 0) {
            if (!$request->filled('quarter') || !$request->filled('actual') || !$request->filled('target')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'When weight is provided, quarter, actual and target must also be provided');
            }
        }

        // Validate that if weight1 is provided, quarter1/actual1/target1 are also provided
        if ($weight1 > 0) {
            if (!$request->filled('quarter1') || !$request->filled('actual1') || !$request->filled('target1')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'When weight1 is provided, quarter1, actual1 and target1 must also be provided');
            }
        }

        // If only one weight is provided, set the other to complete 100
        if ($weight == 0) {
            // Check if weight1 equals 100, otherwise return error
            if ($weight1 != 100) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Weight1 must equal 100');
            }
        } elseif ($weight1 == 0) {
            if ($weight != 100) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Weight must equal 100');
            }
        }
        // If both weights are provided but don't sum to 100
        elseif ($weight + $weight1 != 100) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'The sum of weight and weight1 must equal to 100');
        }

        $request->merge([
            'encoded_by' => auth()->user()->username,
            'weight' => $weight,
            'weight1' => $weight1
        ]);

        // Handle file upload for the outcome file
        if ($request->hasFile('outcome_file')) {
            $filePath = $request->file('outcome_file')->store('outcomes', 'public');
            $request->merge(['outcome_file' => $filePath]);
        }

        // Set default values to 0 if any of the physical accomplishment fields are null
        foreach (['actual', 'target', 'actual1', 'target1'] as $field) {
            if (is_null($request->input($field))) {
                $request->merge([$field => 0]);
            }
        }

        $slippage = (($weight/100 * $request->input('actual')) + ($weight1/100 * $request->input('actual1'))) - (($weight/100 * $request->input('target')) + ($weight1/100 * $request->input('target1')));
        $overall_target = ($weight/100 * $request->input('target')) + ($weight1/100 * $request->input('target1'));

        // Find the physical accomplishment by ID
        $physical = PhysicalAccomplishment::findOrFail($id);

        // Update the physical accomplishment
        $physical->update([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'project_type' => $request->input('project_type'),
            'year' => $request->input('year'),
            'quarter' => $request->input('quarter'),
            'weight' => $weight,
            'actual' => $request->input('actual'),
            'target' => $request->input('target'),
            'project_type1' => $request->input('project_type1'),
            'year1' => $request->input('year1'),
            'quarter1' => $request->input('quarter1'),
            'weight1' => $weight1,
            'actual1' => $request->input('actual1'),
            'target1' => $request->input('target1'),


            'overall_accomplishment' => ($weight/100 * $request->input('actual')) + ($weight1/100 * $request->input('actual1')),
            'overall_target' => ($weight/100 * $request->input('target')) + ($weight1/100 * $request->input('target1')),
            'slippage' => $slippage,
            'remarks' => $slippage < 0 ? 'BEHIND' : ($slippage > 0 ? 'AHEAD' : 'ON-TIME'),
            'slippage_end_of_quarter' => 100 - $slippage - $overall_target,
            'outcome_file' => $request->file('outcome_file') ?? $physical->outcome_file,
            'encoded_by' => $request->input('encoded_by'),
        ]);

        return redirect()->route('projects.index')->with('success', 'Physical Accomplishment updated successfully.');
    }

    // Update the specified project's financial accomplishments in storage
    public function updateFourthTab(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            // 'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table
            // 'project_name' => 'required|string|exists:projects,project_name',
            'orig_budget' => 'required|numeric|min:0',
            'currency' => 'nullable|string|max:255',
            'rate' => 'required|numeric|min:0',
            'budget' => 'required|numeric|min:0',
            'lp' => 'nullable|numeric|min:0',
            'gp' => 'nullable|numeric|min:0',
            'gph_counterpart' => 'nullable|numeric|min:0',
            'disbursement' => 'required|numeric|min:0',
        ]);

        // Fetch the financial accomplishment by ID
        $financial = FinancialAccomplishment::findOrFail($id);


        // Calculate percentage of time elapsed
        $p_disbursement = 0; // Default value
        if ($request->input('budget') > 0) {
            $p_disbursement = number_format(($request->input('disbursement') / $request->input('budget')) * 100, 2);
        }

        // Update the financial accomplishment with the validated data
        $financial->update([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'budget' => $request->input('budget'),
            'currency' => $request->input('currency'),
            'rate' => $request->input('rate'),
            'orig_budget' => $request->input('orig_budget'),
            'lp' => $request->input('lp'),
            'gp' => $request->input('gp'),
            'gph_counterpart' => $request->input('gph_counterpart'),
            'disbursement' => $request->input('disbursement'),
            'p_disbursement' => $p_disbursement
        ]);

        // Redirect to the index with a success message
        return redirect()->route('projects.index')->with('success', 'Financial Accomplishment updated successfully.');
    }
    // Update the specified project's levels in storage
    public function updateThirdTab(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            // 'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table
            // 'project_name' => 'required|string|exists:projects,project_name',
            'level1' => 'nullable|string|max:255',
            'level2' => 'nullable|string|max:255',
            'level3' => 'required|string|max:255',
            'l_budget' => 'required|numeric|min:0',
        ]);

        // Fetch the level by ID
        $level = Level::findOrFail($id);

        // Update the level with the validated data
        $level->update([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'level1' => $request->input('level1'),
            'level2' => $request->input('level2'),
            'level3' => $request->input('level3'),
            'l_budget' => $request->input('l_budget'),
        ]);

        // Redirect to the index with a success message
        return redirect()->route('projects.index')->with('success', 'Health Area updated successfully.');
    }

    // Update the specified project's implementation schedule in storage
    public function updateSecondTab(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            // 'project_id' => 'required|string|exists:projects,project_id|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/', // Ensure each project_id exists in the projects table
            // 'project_name' => 'required|string|exists:projects,project_name',
            'start_date' => 'required|date',
            // 'interim_date' => 'nullable|date|after:start_date',
            'end_date' => 'required|date|after:start_date',
            'extension' => 'nullable|date|after:end_date',
        ]);

        // Find the implementation schedule by ID
        $implementation = ImplementationSchedule::findOrFail($id);

        // Update the implementation schedule
        $implementation->update([
            'project_id' => $request->input('project_id'),
            'project_name' => $request->input('project_name'),
            'start_date' => $request->input('start_date'),
            // 'interim_date' => $request->input('interim_date'),
            'end_date' => $request->input('end_date'),
            'extension' => $request->input('extension'),
            // 'duration' => $duration,
            // 'time_elapsed' => $timeElapsed,
            // 'p_time_elapsed' => $p_time_elapsed,
        ]);

        // Redirect back to the projects index with a success message
        return redirect()->route('projects.index')->with('success', 'Implementation Schedule updated successfully.');
    }


    // Update the specified project in storage
    public function update(Request $request, $id)
    {
        // Validate the request data
        $request->validate([
            // 'project_id' => 'required|string|max:255|regex:/^[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{2}-[A-Za-z0-9]{1,3}$/',
            'project_name' => 'nullable|string|max:255',
            'short_title' => 'required|string|max:255',
            'funding_source' => 'nullable|string|max:255',
            // 'donor' => 'nullable|string|max:255',
            'depdev' => 'nullable|string|max:255',
            'management' => 'nullable|string|max:255',
            'gph' => 'nullable|string|max:255',
            'fund_type' => 'required|string|max:255',
            'desk_officer' => 'nullable|string|max:255',
            'alignment' => 'nullable|array|max:255',
            'environmental' => 'nullable|string',
            'health_facility' => 'nullable|array|max:255',
            'development_objectives' => 'required|string',
            'sector' => 'nullable|array|max:255',
            // 'sector_others' => 'nullable|string|max:255',
            'sites' => 'required|string|max:255',
            'site_specific_reg' => 'nullable|array',
            'site_specific_prov' => 'nullable|array',
            'site_specific_city' => 'nullable|array',
            // 'agreement' => 'nullable|file|max:25600|mimes:pdf,doc,docx,xls,xlsx,csv,jpg,png', // Increased to 25MB
            'agreement' => 'nullable|file|max:25600|mimes:pdf', // Increased to 25MB
            'site_specific' => 'nullable|array',
            // 'classification' => 'nullable|string|max:255',
            // 'uhc' => 'nullable|string|max:255',
            // 'uhc_is' => 'nullable|boolean',
            'status' => 'nullable|string|max:255',
            'outcome' => 'nullable|string',
        ]);

        // Find the project by ID
        $project = Project::findOrFail($id);

        // Set completed_date if status is changed to 'completed'
        if ($request->input('status') === 'Completed' && $project->completed_date === null) {
            $request->merge([
                'completed_date' => Carbon::now('Asia/Manila')
            ]);
        } elseif ($request->input('status') !== 'Completed' && $project->completed_date !== null) {
            $request->merge([
                'completed_date' => null
            ]);
        }

        // Check if the project_id exists in the projects table
        if (!Project::where('project_id', $request->input('project_id'))->exists()) {
            return redirect()->route('projects.index')->with('error', 'Project ID does not exist in the projects table.');
        }

        // Convert sector array to comma-separated string
        if (isset($request->sector)) {
            $request->merge([
                'sector' => implode(', ', $request->sector),
            ]);
        }
        // If sector is missing (all unchecked), set to 'N/A'
        if (!$request->has('sector')) {
            $request->merge(['sector' => 'N/A']);
        }

        // Convert alignment array to comma-separated string
        if (isset($request->alignment)) {
            $request->merge([
                'alignment' => implode(', ', $request->alignment),
            ]);
        }
        // If alignment is missing (all unchecked), set to 'N/A'
        if (!$request->has('alignment')) {
            $request->merge(['alignment' => 'N/A']);
        }
        // Convert health_facility array to comma-separated string
        if (isset($request->health_facility)) {
            $request->merge([
                'health_facility' => implode(', ', $request->health_facility),
            ]);
        }
        // If health_facility is missing (all unchecked), set to 'N/A'
        if (!$request->has('health_facility')) {
            $request->merge(['health_facility' => 'N/A']);
        }

        // Convert site_specific_reg array to comma-separated string
        if (isset($request->site_specific_reg)) {
            $regions = DB::table('ref_region')->select('nscb_reg_name')->whereIn('regcode', $request->site_specific_reg)->get();
            $request->merge([
                'site_specific_reg' => implode(', ', $regions->pluck('nscb_reg_name')->toArray()),
            ]);
        }

        // Convert site_specific_prov array to comma-separated string of provname
        if (isset($request->site_specific_prov)) {
            $provinces = DB::table('ref_prov')->select('provname')->whereIn('provcode', $request->site_specific_prov)->get();
            $request->merge([
                'site_specific_prov' => implode(', ', $provinces->pluck('provname')->toArray()),
            ]);
        }

        // Convert site_specific_city array to comma-separated string
        if (isset($request->site_specific_city)) {
            $request->merge([
                'site_specific_city' => implode(', ', $request->site_specific_city),
            ]);
        }

        // If funding_source is updated, update donor accordingly
        if ($request->has('funding_source')) {
            $donorCode = DB::table('ref_funds')->where('funds_desc', $request->input('funding_source'))->value('funds_code');
            $request->merge([
                'donor' => $donorCode,
            ]);
        }

        // Prepare the data for the project
        $request->merge([
            'fund_management' => $request->input('management') . ', ' . $request->input('fund_type'),
        ]);

        // Handle file upload for the agreement file
        if ($request->hasFile('agreement')) {
            $filePath = $request->file('agreement')->store('agreements', 'public');
            $request->merge(['agreement' => $filePath]);
        }

        // Update the project with the validated data
        $project->update($request->all());

        // Update all connected implementation schedules
        ImplementationSchedule::where('project_id', $request->input('project_id'))->update([
            'project_name' => $request->input('project_name'),
        ]);

        // Update all connected levels
        Level::where('project_id', $request->input('project_id'))->update([
            'project_name' => $request->input('project_name'),
        ]);

        // Update all connected financial accomplishments
        FinancialAccomplishment::where('project_id', $request->input('project_id'))->update([
            'project_name' => $request->input('project_name'),
        ]);

        // Update all connected physical accomplishments
        PhysicalAccomplishment::where('project_id', $request->input('project_id'))->update([
            'project_name' => $request->input('project_name'),
        ]);

        // Redirect to the index with a success message
        return redirect()->route('projects.index')->with('success', 'Project and related Implementation Schedules, Health Areas, Financial Accomplishments, and Physical Accomplishments updated successfully.');
    }

    // Remove the specified project from storage
    public function destroy($id)
    {
        try {
            $project = Project::findOrFail($id); // Find the project by ID

            // Delete related implementation schedules
            ImplementationSchedule::where('project_id', $project->project_id)->delete();

            // Delete related levels
            Level::where('project_id', $project->project_id)->delete();

            // Delete related financial accomplishments
            FinancialAccomplishment::where('project_id', $project->project_id)->delete();

            // Delete related physical accomplishments
            PhysicalAccomplishment::where('project_id', $project->project_id)->delete();

            // Delete the project
            $project->delete();

            return redirect()->route('projects.index')->with('success', 'Project and related Implementation schedules, Health Areas, Financial Accomplishments, and Physical Accomplishments deleted successfully.');
        } catch (\Exception $e) {
            // Handle the exception and redirect back with an error message
            return redirect()->route('projects.index')->with('error', 'Failed to delete the project. Please try again.');
        }
    }

    // Remove the specified implementation schedule from storage
    public function destroyImplementationSchedule($id)
    {
        try {
            $implementation = ImplementationSchedule::findOrFail($id); // Find the implementation schedule by ID
            $implementation->delete(); // Delete the implementation schedule

            return redirect()->route('projects.index')->with('success', 'Implementation schedule deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')->with('error', 'Failed to delete the implementation schedule. Please try again.');
        }
    }

    // Remove the specified level from storage
    public function destroyLevel($id)
    {
        try {
            $level = Level::findOrFail($id); // Find the level by ID
            $level->delete(); // Delete the level

            return redirect()->route('projects.index')->with('success', 'Health Areas deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')->with('error', 'Failed to delete the level. Please try again.');
        }
    }

    // Remove the specified financial accomplishment from storage
    public function destroyFinancial($id)
    {
        try {
            $financial = FinancialAccomplishment::findOrFail($id); // Find the financial accomplishment by ID
            $financial->delete(); // Delete the financial accomplishment

            return redirect()->route('projects.index')->with('success', 'Financial Accomplishment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')->with('error', 'Failed to delete the financial accomplishment. Please try again.');
        }
    }

    // Remove the specified physical accomplishment from storage
    public function destroyPhysical($id)
    {
        try {
            $physical = PhysicalAccomplishment::findOrFail($id); // Find the physical accomplishment by ID
            $physical->delete(); // Delete the physical accomplishment

            return redirect()->route('projects.index')->with('success', 'Physical Accomplishment deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('projects.index')->with('error', 'Failed to delete the physical accomplishment. Please try again.');
        }
    }
}
