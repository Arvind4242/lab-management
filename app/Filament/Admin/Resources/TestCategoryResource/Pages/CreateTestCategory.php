<?php

namespace App\Filament\Admin\Resources\TestCategoryResource\Pages;

use App\Filament\Admin\Resources\TestCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestCategory extends CreateRecord
{
    protected static string $resource = TestCategoryResource::class;
    // protected static ?string $navigationIcon = 'heroicon-o-document-plus';
    // protected static ?string $navigationLabel = 'Create Test';
    // protected static ?string $navigationGroup = 'Tests';
    // protected static ?string $slug = 'test-creation'; // Corrected here
    // protected static string $view = 'filament.admin.pages.test-creation';
}
