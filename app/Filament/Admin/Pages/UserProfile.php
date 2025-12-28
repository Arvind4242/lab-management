<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Page
{
    protected static string $view = 'filament.admin.pages.user-profile';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profile';

    protected static ?int $navigationSort = 99;

    // protected static ?string $maxContentWidth = 'full';

    // ✅ ONLY USER CAN SEE THIS PAGE
    public static function canAccess(): bool
    {
        return Auth::check() && Auth::user()->role === 'user';
    }
}
