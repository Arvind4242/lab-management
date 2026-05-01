<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lab;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'         => User::where('role', 'user')->count(),
            'total_labs'          => Lab::count(),
            'total_reports'       => Report::count(),
            'reports_today'       => Report::whereDate('created_at', today())->count(),
            'active_subs'         => Subscription::where('is_active', true)->count(),
            'expired_subs'        => Subscription::where('is_active', false)->count(),
        ];

        $recentUsers = User::where('role', 'user')
            ->with('lab')
            ->latest()
            ->take(5)
            ->get();

        $recentReports = Report::with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentSubs = Subscription::with(['user', 'lab'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentReports', 'recentSubs'));
    }
}
