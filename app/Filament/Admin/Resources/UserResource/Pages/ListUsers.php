<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        // Only show create button for admins
        if (Auth::user()->role === 'admin') {
            return [
                Actions\CreateAction::make(),
            ];
        }

        // For normal users, no header actions
        return [];
    }
}
