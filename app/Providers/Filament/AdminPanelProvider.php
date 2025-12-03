<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Pages\CustomLogin;
use App\Filament\Pages\Auth\Login;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Actions\CreateAction;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panel->configureUsing(function () {
            //configure components
            CreateAction::configureUsing(function (CreateAction $action): void {
                $action->createAnother(false);
            });
        });
        return $panel
            ->default()
            ->id('admin')
            ->brandName('Toko Rabskubread')
            ->path('admin')
            ->login(CustomLogin::class)
            ->colors([
                'primary' => Color::Zinc,
            ])
            ->resourceEditPageRedirect('index')
            ->resourceCreatePageRedirect('index')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
            ->plugins(
                [
                    FilamentShieldPlugin::make()
                        ->modelLabel('Peran & Hak Akses')
                        ->pluralModelLabel('Peran & Hak Akses')
                        ->navigationGroup('Manajemen Adminstrator')
                        ->navigationLabel('Peran & Hak Akses')
                        ->navigationSort(1)
                        ->localizePermissionLabels()
                        ->gridColumns([
                            'default' => 1,
                            'sm' => 2,
                            'lg' => 3
                        ])
                        ->sectionColumnSpan(1)
                        ->checkboxListColumns([
                            'default' => 1,
                            'sm' => 2,
                            'lg' => 4,
                        ])
                        ->resourceCheckboxListColumns([
                            'default' => 1,
                            'sm' => 2,
                        ]),
                ]
            )
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
