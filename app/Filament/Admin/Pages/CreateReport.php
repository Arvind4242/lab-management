<?php
namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use App\Models\TestPanel;
use App\Models\TestCategory;
use App\Models\Test;

class CreateReport extends Page
{
    protected static ?string $slug = 'create-report';
    protected static bool $shouldRegisterNavigation = true;
    protected static string $view = 'filament.admin.pages.create-report'; // 👈 static view

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getViewData(): array
    {
        return [
            'categories' => TestCategory::all(),
            'panels' => TestPanel::pluck('name', 'id')->toArray(),
            'panelCategories' => TestPanel::pluck('category_id', 'id')->toArray(),
            'tests' => Test::all(),
        ];
    }
}
