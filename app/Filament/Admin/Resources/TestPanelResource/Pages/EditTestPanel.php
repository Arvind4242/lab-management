<?php

namespace App\Filament\Admin\Resources\TestPanelResource\Pages;

use App\Filament\Admin\Resources\TestPanelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestPanel extends EditRecord
{
    protected static string $resource = TestPanelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
