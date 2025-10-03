<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SectiononeController;
use App\Http\Controllers\SectiontwoController;
use App\Http\Controllers\SectionthreeController;
use App\Http\Controllers\SectionfourController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\AuthenticatedSessionController;

use App\Http\Controllers\LevelController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UseraccountController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeographicDistributionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;


Route::get('/', function () {
    return redirect('login');
});

// Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::middleware(['auth', 'prevent-back', 'validate-session'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});


Route::get('/financial-accomplishments', [DashboardController::class, 'getFinancialAccomplishments'])->middleware(['auth']);

Route::get('/introduction', function () {
    return view('introduction');
})->middleware(['auth', 'verified'])->name('introduction');


// Route::get('register', [RegisteredUserController::class, 'create'])
//         ->name('register');

// Route::post('register', [RegisteredUserController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
});


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/check-username', [UseraccountController::class, 'checkUsername']);
Route::get('/check-email', [UseraccountController::class, 'checkEmail']);

Route::resource('useraccount', UseraccountController::class)->middleware(['auth']);
//Project Information Management
// 1st Tab
Route::resource('projects', ProjectController::class)->middleware(['auth']);
// 2nd Tab
Route::post('/projects/store-second-tab', [ProjectController::class, 'storeSecondTab'])->name('projects.storeSecondTab');
Route::delete('/implementation-schedules/{id}', [ProjectController::class, 'destroyImplementationSchedule'])
    ->name('projects.destroyImplementationSchedule');
Route::get('/projects/{id}/edit-second-tab', [ProjectController::class, 'editSecondTab'])->name('projects.editSecondTab');
Route::put('/projects/{id}/update-second-tab', [ProjectController::class, 'updateSecondTab'])->name('projects.updateSecondTab');
// 3rd Tab
Route::post('/projects/store-third-tab', [ProjectController::class, 'storeThirdTab'])->name('projects.storeThirdTab');
Route::delete('/levels/{id}', [ProjectController::class, 'destroyLevel'])
    ->name('projects.destroyLevel');
Route::get('/projects/{id}/edit-third-tab', [ProjectController::class, 'editThirdTab'])->name('projects.editThirdTab');
Route::put('/projects/{id}/update-third-tab', [ProjectController::class, 'updateThirdTab'])->name('projects.updateThirdTab');
// 4th Tab
Route::post('/projects/store-fourth-tab', [ProjectController::class, 'storeFourthTab'])->name('projects.storeFourthTab');
Route::delete('/financial-accomplishments/{id}', [ProjectController::class, 'destroyFinancial'])
    ->name('projects.destroyFinancial');
Route::get('/projects/{id}/edit-fourth-tab', [ProjectController::class, 'editFourthTab'])->name('projects.editFourthTab');
Route::put('/projects/{id}/update-fourth-tab', [ProjectController::class, 'updateFourthTab'])->name('projects.updateFourthTab');
// 5th Tab
Route::post('/projects/store-fifth-tab', [ProjectController::class, 'storeFifthTab'])->name('projects.storeFifthTab');
Route::delete('/physical-accomplishments/{id}', [ProjectController::class, 'destroyPhysical'])
    ->name('projects.destroyPhysical');
Route::get('/projects/{id}/edit-fifth-tab', [ProjectController::class, 'editFifthTab'])->name('projects.editFifthTab');
Route::put('/projects/{id}/update-fifth-tab', [ProjectController::class, 'updateFifthTab'])->name('projects.updateFifthTab');

//Section One
Route::get('fapslist', [SectiononeController::class, 'fapslist'])->name('fapslist');

//Section Two
Route::get('geographic_distribution', [GeographicDistributionController::class, 'index'])->name('geographic_distribution');
Route::get('geographic_distribution/geojson', [GeographicDistributionController::class, 'getRealTimeGeoJSON'])->name('geographic_distribution.geojson');
Route::get('health_area_distribution', [SectiontwoController::class, 'healthAreasDistribution'])->name('health_area_distribution');
Route::post('health-areas-distribution/filtered-data', [SectiontwoController::class, 'getFilteredHealthAreasData'])->name('health_areas_distribution.filtered_data');
Route::get('health_area_distribution/report', [SectiontwoController::class, 'healthAreasDistributionReport'])->name('health_area_distribution.report');
Route::get('overall_area_distribution', [SectiontwoController::class, 'overallAreaDistribution'])->name('overall_area_distribution');
Route::get('overall_area_distribution/report', [SectiontwoController::class, 'overallAreaDistributionReport'])->name('overall_area_distribution.report');

//Section Three
Route::get('funding_source', [SectionthreeController::class, 'fundingSource'])->name('funding_source');
Route::get('health_area', [SectionthreeController::class, 'healthArea'])->name('health_area');

//Section Four
Route::get('report', [SectionfourController::class, 'report'])->name('report');
Route::get('/about', [AboutController::class, 'index'])->middleware(['auth', 'verified'])->name('about');
Route::get('/generate-dashboard-summary-report', [SectionfourController::class, 'generateDashboardSummaryReport']);
Route::get('/preview-dashboard-summary-report', [SectionfourController::class, 'previewDashboardSummaryReport'])->name('dashboard.summary.preview');
Route::get('/generate-report', [SectionfourController::class, 'generateReport']);




Route::get('/get-level2-options', [LevelController::class, 'getLevel2Options']);
Route::get('/get-level3-options', [LevelController::class, 'getLevel3Options']);

// Route::get('/projects/{project}/levels', [LevelController::class, 'getLevels']);
// Route::resource('levels', LevelController::class)->middleware('auth');

Route::get('/geographic/distribution/data', [GeographicDistributionController::class, 'geographicDistributionData'])
    ->name('geographic.distribution.data');

Route::get('/geographic/distribution/geojson', [GeographicDistributionController::class, 'getProvincesGeoJSON'])
    ->name('geographic.distribution.geojson');

// Display geographic distribution map
// Route::get('/geographic-distribution', [GeographicDistributionController::class, 'index'])
//     ->name('geographic.distribution');

// API endpoint for project distribution data
// Route::get('/api/project-distribution', [GeographicDistributionController::class, 'getProjectDistribution']);


// Test mail configuration
// Route::get('/test-mail', function () {
//     $to = request('email', 'test@example.com');

//     try {
//         Mail::raw('This is a test email from your Laravel application.', function($message) use ($to) {
//             $message->to($to)
//                     ->subject('Test Email');
//         });

//         return "Test email sent successfully to {$to}";
//     } catch (\Exception $e) {
//         return "Error sending email: " . $e->getMessage();
//     }
// });

Route::get('/dashboard/physical-project-list', [\App\Http\Controllers\DashboardController::class, 'physicalProjectList'])
    ->name('dashboard.physical-project-list');

Route::get('/dashboard/funding-project-list', [\App\Http\Controllers\DashboardController::class, 'fundingProjectList'])
    ->name('dashboard.funding-project-list');

Route::get('/dashboard/fund-type-project-list', [\App\Http\Controllers\DashboardController::class, 'fundTypeProjectList'])
    ->name('dashboard.fund-type-project-list');

Route::get('/dashboard/geo-project-list', [\App\Http\Controllers\DashboardController::class, 'geoProjectList'])
    ->name('dashboard.geo-project-list');

Route::get('/dashboard/region-project-list', [\App\Http\Controllers\DashboardController::class, 'regionProjectList'])
    ->name('dashboard.region-project-list');

Route::get('/dashboard/financial-project-list', [\App\Http\Controllers\DashboardController::class, 'financialProjectList'])
    ->name('dashboard.financial-project-list');

Route::get('/dashboard/depdev-project-list', [\App\Http\Controllers\DashboardController::class, 'depdevProjectList'])->name('dashboard.depdev-project-list');
Route::get('/dashboard/management-project-list', [\App\Http\Controllers\DashboardController::class, 'managementProjectList'])->name('dashboard.management-project-list');

Route::get('/dashboard/portfolio-project-list', [\App\Http\Controllers\DashboardController::class, 'portfolioProjectList'])->name('dashboard.portfolio-project-list');

Route::get('/dashboard/healtharea-level1-project-list', [\App\Http\Controllers\DashboardController::class, 'healthAreaLevel1ProjectList'])->name('dashboard.healtharea-level1-project-list');
Route::get('/dashboard/healtharea-level2-project-list', [\App\Http\Controllers\DashboardController::class, 'healthAreaLevel2ProjectList'])->name('dashboard.healtharea-level2-project-list');
Route::get('/dashboard/healtharea-level3-project-list', [\App\Http\Controllers\DashboardController::class, 'healthAreaLevel3ProjectList'])->name('dashboard.healtharea-level3-project-list');

Route::get('/audit-logs', [\App\Http\Controllers\AuditLogController::class, 'index'])->name('audit.logs');

// Clear welcome modal session
Route::post('/clear-welcome-modal', function () {
    session()->forget('show_welcome_modal');
    return response()->json(['success' => true]);
})->middleware(['auth'])->name('clear-welcome-modal');

require __DIR__.'/auth.php';

