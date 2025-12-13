<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Http\Middleware\AdminOnly;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandLogo(asset('/storage/images/logo.png'))
            ->brandLogoHeight('50px')
            ->colors([
    'primary' => '#ec4899',  // Using pink-500
])

            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                \App\Filament\Admin\Widgets\StatsOverviewWidget::class, // ✅ Only custom widget
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                AdminOnly::class,
            ]);
    }

public function boot(): void
{
    \Filament\Support\Facades\FilamentView::registerRenderHook(
        'panels::styles.before',
        fn (): string => '
          <style>
                /* Gradient background for entire Filament panel */
                // body, .fi-body, .fi-layout {
                //     background: linear-gradient(to bottom right, #0f172a, #4c1d95, #0f172a) !important;
                //     background-attachment: fixed !important;
                // }

                /* Sidebar background — optional */
                .fi-sidebar {
                    background-color: rgba(216, 216, 216, 0.4) !important;
                    backdrop-filter: blur(20px);
                }

                /* Logo fix */
                .fi-sidebar-header img,
                .fi-logo img {
                    width: 150px !important;
                    height: 60px !important;
                    object-fit: contain;
                }
            </style>
        '
    );
}



}
