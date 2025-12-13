<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Lab;
use App\Models\Report;
use App\Models\Subscription;
use App\Models\TestCategory;
use App\Models\TestPackage;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        // ✅ NORMAL USER → show their reports by days
        if ($user->role !== 'admin') {
            $today = Carbon::today();

            $todayReports = Report::where('user_id', $user->id)
                ->whereDate('test_date', $today)
                ->count();

            $last7DaysReports = Report::where('user_id', $user->id)
                ->whereDate('test_date', '>=', $today->copy()->subDays(6)) // including today
                ->count();

            $last30DaysReports = Report::where('user_id', $user->id)
                ->whereDate('test_date', '>=', $today->copy()->subDays(29))
                ->count();

            // Optional: per-day chart for last 7 days
            $last7DaysLabels = [];
            $last7DaysCounts = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = $today->copy()->subDays($i);
                $last7DaysLabels[] = $date->format('d M');

                $last7DaysCounts[] = Report::where('user_id', $user->id)
                    ->whereDate('test_date', $date)
                    ->count();
            }

            return [
                Stat::make('Today\'s Reports', $todayReports)
                    ->description('Reports created today')
                    ->descriptionIcon('heroicon-m-calendar-days')
                    ->color('info'),

                Stat::make('Last 7 Days', $last7DaysReports)
                    ->description('Reports in the last 7 days')
                    ->descriptionIcon('heroicon-m-clock')
                    ->color('primary')
                    ->chart($last7DaysCounts), // mini sparkline chart
                    // ->extraAttributes(['x-data' => json_encode($last7DaysLabels)]), // only if you want to use labels in view

                Stat::make('Last 30 Days', $last30DaysReports)
                    ->description('Reports in the last 30 days')
                    ->descriptionIcon('heroicon-m-chart-bar')
                    ->color('success'),
            ];
        }

        // ✅ ADMIN → keep your overall system stats
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Reports', Report::count())
                ->description('All reports in system')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Total Labs', Lab::count())
                ->description('Registered labs')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('warning'),

            Stat::make('Total Subscriptions', Subscription::count())
                ->description('Active subscriptions')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make('Test Packages', TestPackage::count())
                ->description('Available packages')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Test Categories', TestCategory::count())
                ->description('Test categories')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),
        ];
    }
}
