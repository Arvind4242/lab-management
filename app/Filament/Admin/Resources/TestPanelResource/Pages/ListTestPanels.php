<?php

namespace App\Filament\Admin\Resources\TestPanelResource\Pages;

use App\Filament\Admin\Resources\TestPanelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestPanels extends ListRecords
{
    protected static string $resource = TestPanelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
