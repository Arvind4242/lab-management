<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin-dashboard', function () {
        return view('admin.dashboard');
    });
});


Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth'])->group(function() {

    // Report create/store/edit/update
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports/store', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');

    // AJAX: fetch tests by panel
    Route::get('/reports/panel-tests/{panel}', [ReportController::class, 'getPanelTests'])->name('reports.panel.tests');
    Route::post('/reports/panel-tests', [ReportController::class, 'getPanelTestsPost'])->name('reports.panel.tests.post');
    Route::get('/reports/test/{id}', [ReportController::class, 'getSingleTest']);


    // Print & Download
    Route::get('/reports/{report}/print', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');

    // Admin routes (optional, if you want separate prefix)
    Route::prefix('admin')->group(function () {
        Route::get('/reports/{report}/print', [ReportController::class, 'print'])->name('reports.admin.print');
        Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.admin.download');
    });

Route::get('/testView/{reportId}', [ReportController::class, 'testView'])->name('report.testView');
});
