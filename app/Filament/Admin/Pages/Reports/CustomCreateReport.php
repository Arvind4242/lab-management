<?php

namespace App\Filament\Admin\Pages\Reports;

use Filament\Pages\Page;

class CustomCreateReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.admin.reports.custom-create-report';
}
