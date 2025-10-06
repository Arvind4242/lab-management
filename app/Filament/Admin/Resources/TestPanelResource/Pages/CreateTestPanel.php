<?php

namespace App\Filament\Admin\Resources\TestPanelResource\Pages;

use App\Filament\Admin\Resources\TestPanelResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestPanel extends CreateRecord
{
    protected static string $resource = TestPanelResource::class;
}
