<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Report;
use App\Models\Lab;
use App\Models\Subscription;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3]),

            Stat::make('Total Reports', Report::count())
                ->description('All reports in system')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('info')
                ->chart([3, 5, 4, 6, 7, 3, 5, 6]),

            Stat::make('Total Labs', Lab::count())
                ->description('Registered labs')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('warning'),

            Stat::make('Total Subscriptions', Subscription::count())
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
