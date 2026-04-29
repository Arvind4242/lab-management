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
            ->pages([
    Pages\Dashboard::class,
    \App\Filament\Admin\Pages\CreateReport::class, // 👈 ye line add karo
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
                // AdminOnly::class,
            ]);
    }
public function boot(): void
{
    config(['filament.exports.enabled' => false]);
    config(['filament.imports.enabled' => false]);
    \Filament\Support\Facades\FilamentView::registerRenderHook(
        'panels::styles.before',
        fn (): string => '
          <style>
                /* Sidebar */
                .fi-sidebar {
                    background-color: rgba(255, 255, 255, 0.85) !important;
                    backdrop-filter: blur(16px);
                    border-right: 1px solid rgba(0,0,0,0.06);
                }

                /* Logo */
                .fi-sidebar-header img,
                .fi-logo img {
                    width: 150px !important;
                    height: 60px !important;
                    object-fit: contain;
                }

                /* Nav group headings */
                .fi-sidebar-group-label {
                    font-size: 0.65rem !important;
                    font-weight: 700 !important;
                    letter-spacing: 0.08em !important;
                    text-transform: uppercase !important;
                }

                /* Section cards — subtle shadow */
                .fi-section {
                    box-shadow: 0 1px 3px 0 rgba(0,0,0,0.04), 0 1px 2px -1px rgba(0,0,0,0.04) !important;
                }

                /* Table header */
                .fi-ta-header-cell {
                    font-size: 0.7rem !important;
                    letter-spacing: 0.06em !important;
                    text-transform: uppercase !important;
                }

                /* Repeater items — cleaner border */
                .fi-fo-repeater-item {
                    border-radius: 0.5rem !important;
                    border-color: rgba(0,0,0,0.08) !important;
                }

                /* Stats widget — rounded corners */
                .fi-wi-stats-overview-stat {
                    border-radius: 0.75rem !important;
                }
            </style>
        '
    );
}



}
