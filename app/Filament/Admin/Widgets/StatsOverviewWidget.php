<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Report;
use Illuminate\Support\Facades\Auth;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();

        // If normal user, show only their reports
        if ($user->role !== 'admin') {
            $todayReports = Report::where('user_id', $user->id)
                ->whereDate('test_date', today())
                ->count();

            $allReports = Report::where('user_id', $user->id)->count();

            return [
                Stat::make('Today\'s Reports', $todayReports)
                    ->description('Reports created today')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('info'),

                Stat::make('All Reports', $allReports)
                    ->description('Total reports you created')
                    ->descriptionIcon('heroicon-m-document-text')
                    ->color('success'),
            ];
        }

        // Admin sees full dashboard (optional, keep your existing stats)
        return [
            Stat::make('Total Users', \App\Models\User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Reports', Report::count())
                ->description('All reports in system')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info'),

            Stat::make('Total Labs', \App\Models\Lab::count())
                ->description('Registered labs')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('warning'),

            Stat::make('Total Subscriptions', \App\Models\Subscription::count())
                ->description('Active subscriptions')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('primary'),

            Stat::make('Test Packages', \App\Models\TestPackage::count())
                ->description('Available packages')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Test Categories', \App\Models\TestCategory::count())
                ->description('Test categories')
                ->descriptionIcon('heroicon-m-folder')
                ->color('info'),
        ];
    }
}
