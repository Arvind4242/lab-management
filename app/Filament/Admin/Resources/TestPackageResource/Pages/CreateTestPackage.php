<?php

namespace App\Filament\Admin\Resources\TestPackageResource\Pages;

use App\Filament\Admin\Resources\TestPackageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestPackage extends CreateRecord
{
    protected static string $resource = TestPackageResource::class;
}
