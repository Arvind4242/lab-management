<?php

namespace App\Filament\Admin\Resources\TestPackageResource\Pages;

use App\Filament\Admin\Resources\TestPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestPackages extends ListRecords
{
    protected static string $resource = TestPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
