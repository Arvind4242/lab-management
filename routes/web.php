<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AdminOnly;

// Public pages
Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/signup', fn () => view('auth.signup'))->name('signup');
Route::get('/login', fn () => view('auth.login'))->name('login');

// Auth actions
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

// Admin protected pages
Route::middleware(['auth', AdminOnly::class])->prefix('admin')->group(function () {
    Route::get('/dashboard', fn () => view('admin.dashboard'))->name('admin.dashboard');
    // Route::get('/create-report', [ReportController::class, 'create'])->name('reports.admin.create');
});

// User protected report actions
Route::middleware(['auth'])->group(function () {

    // Report CRUD (user side)
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.user.create');
    Route::post('/reports/store', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');

    // AJAX routes
    Route::get('/reports/panel-tests/{panel}', [ReportController::class, 'getPanelTests'])->name('reports.panel.tests');
    Route::post('/reports/panel-tests', [ReportController::class, 'getPanelTestsPost'])->name('reports.panel.tests.post');
    Route::get('/reports/test/{id}', [ReportController::class, 'getSingleTest'])->name('reports.single.test');

    // Print & Download
    Route::get('/reports/{report}/print', [ReportController::class, 'print'])->name('reports.print');
    Route::get('/reports/{report}/download', [ReportController::class, 'download'])->name('reports.download');

    // Extra test view
    Route::get('/testView/{reportId}', [ReportController::class, 'testView'])->name('report.testView');

});
