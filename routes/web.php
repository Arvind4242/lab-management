<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserCtrl;
use App\Http\Controllers\Admin\LabController as AdminLabCtrl;
use App\Http\Controllers\Admin\TestController as AdminTestCtrl;
use App\Http\Controllers\Admin\TestCategoryController as AdminTestCategoryCtrl;
use App\Http\Controllers\Admin\TestPanelController as AdminTestPanelCtrl;
use App\Http\Controllers\Admin\TestPackageController as AdminTestPackageCtrl;
use App\Http\Controllers\Admin\UnitController as AdminUnitCtrl;
use App\Http\Controllers\Admin\ReportController as AdminReportCtrl;
use App\Http\Controllers\Admin\SubscriptionController as AdminSubscriptionCtrl;
use App\Http\Controllers\Admin\PlanController as AdminPlanCtrl;
use App\Http\Controllers\User\DashboardController as UserDashboard;
use App\Http\Controllers\User\ReportController as UserReportCtrl;
use App\Http\Controllers\User\ProfileController as UserProfileCtrl;
use App\Http\Controllers\User\SubscriptionController as UserSubscriptionCtrl;
use App\Http\Controllers\User\TestPackageController as UserTestPackageCtrl;
use App\Http\Controllers\Payment\RazorpayController;

// Root redirect
Route::get('/', function () {
    if (!auth()->check()) return redirect()->route('login');
    if (auth()->user()->role === 'admin') return redirect()->route('admin.dashboard');
    return redirect()->route('user.dashboard');
});

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegister'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Admin Panel (role = admin) ────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'super_admin'])->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users', AdminUserCtrl::class);
    Route::resource('labs', AdminLabCtrl::class);
    Route::resource('tests', AdminTestCtrl::class);
    Route::resource('test-categories', AdminTestCategoryCtrl::class);
    Route::resource('test-panels', AdminTestPanelCtrl::class);
    Route::resource('test-packages', AdminTestPackageCtrl::class);
    Route::resource('units', AdminUnitCtrl::class);
    Route::resource('plans', AdminPlanCtrl::class);

    Route::get('reports', [AdminReportCtrl::class, 'index'])->name('reports.index');
    Route::get('reports/{report}', [AdminReportCtrl::class, 'show'])->name('reports.show');

    Route::get('subscriptions', [AdminSubscriptionCtrl::class, 'index'])->name('subscriptions.index');
    Route::get('subscriptions/{subscription}', [AdminSubscriptionCtrl::class, 'show'])->name('subscriptions.show');
    Route::patch('subscriptions/{subscription}/toggle', [AdminSubscriptionCtrl::class, 'toggle'])->name('subscriptions.toggle');
});

// ─── User (Lab) Panel ──────────────────────────────────────────────────────────
Route::prefix('dashboard')->name('user.')->middleware(['auth', 'lab_user'])->group(function () {
    Route::get('/', [UserDashboard::class, 'index'])->name('dashboard');

    Route::resource('reports', UserReportCtrl::class);
    Route::get('reports/{report}/print', [UserReportCtrl::class, 'print'])->name('reports.print');
    Route::get('reports/{report}/download', [UserReportCtrl::class, 'download'])->name('reports.download');
    Route::get('ajax/panel-tests/{panel}', [UserReportCtrl::class, 'getPanelTests'])->name('ajax.panel-tests');
    Route::get('ajax/test/{id}', [UserReportCtrl::class, 'getSingleTest'])->name('ajax.single-test');
    Route::get('ajax/package-tests/{package}', [UserTestPackageCtrl::class, 'getTests'])->name('ajax.package-tests');

    Route::resource('packages', UserTestPackageCtrl::class)->except(['show']);

    Route::get('profile', [UserProfileCtrl::class, 'index'])->name('profile');
    Route::put('profile', [UserProfileCtrl::class, 'update'])->name('profile.update');
    Route::put('profile/password', [UserProfileCtrl::class, 'updatePassword'])->name('profile.password');

    Route::get('subscription', [UserSubscriptionCtrl::class, 'index'])->name('subscription');
    Route::get('subscription/plans', [UserSubscriptionCtrl::class, 'plans'])->name('subscription.plans');
    Route::post('subscription/checkout/{plan}', [UserSubscriptionCtrl::class, 'checkout'])->name('subscription.checkout');
    Route::post('subscription/verify', [UserSubscriptionCtrl::class, 'verify'])->name('subscription.verify');
});

// Razorpay webhook (no CSRF)
Route::post('razorpay/webhook', [RazorpayController::class, 'webhook'])->name('razorpay.webhook');
