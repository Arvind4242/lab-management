<?php

namespace App\Filament\Admin\Resources\TestCategoryResource\Pages;

use App\Filament\Admin\Resources\TestCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestCategory extends EditRecord
{
    protected static string $resource = TestCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
