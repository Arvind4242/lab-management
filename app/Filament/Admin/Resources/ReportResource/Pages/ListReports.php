<?php

namespace App\Filament\Admin\Resources\ReportResource\Pages;

use App\Filament\Admin\Resources\ReportResource;
use App\Filament\Admin\Pages\CreateReport;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

   protected function getHeaderActions(): array
{
    return [
        Actions\Action::make('Create Report')
            ->label('Create Report')
            ->icon('heroicon-o-plus')
            ->url(route('reports.admin.create')), // ✅ correct name
    ];
}


}
