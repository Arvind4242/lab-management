<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Subscription;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_reports'  => Report::where('user_id', $user->id)->count(),
            'reports_today'  => Report::where('user_id', $user->id)->whereDate('created_at', today())->count(),
            'reports_month'  => Report::where('user_id', $user->id)->whereMonth('created_at', now()->month)->count(),
        ];

        $subscription = Subscription::where('user_id', $user->id)
            ->with('plan')
            ->latest()
            ->first();

        $recentReports = Report::where('user_id', $user->id)
            ->with('panel')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('stats', 'subscription', 'recentReports'));
    }
}
