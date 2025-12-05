<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Pages\CustomLogin;
use App\Filament\Pages\Auth\Login;
use App\Filament\Widgets\CustomAccountWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Resources\Pages\CreateRecord;
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
            CreateRecord::disableCreateAnother();
            CreateAction::configureUsing(function (CreateAction $action): void {
                $action->createAnother(false);
                $action->color(Color::Green);
            });
            EditAction::configureUsing(function (EditAction $action): void {
                $action->color(Color::Blue);
                $action->button();
            });
            ViewAction::configureUsing(function (ViewAction $action): void {
                $action->color(Color::Gray);
                $action->button();
            });
            DeleteAction::configureUsing(function (DeleteAction $action): void {
                $action->color(Color::Red);
                $action->button();
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
                'laki-laki' => Color::Indigo,
                'perempuan' => Color::Pink,
                'cretae' => Color::Green,
                'edit' => Color::Blue,
                'delete' => Color::Red,
                'view' => Color::Amber,
                'produk' => Color::Teal,
                'gambar' => Color::Sky,
                'preview' => Color::Indigo,
                'pelanggan' => Color::Emerald,
                'alamat' => Color::Lime,
            ])
            ->resourceEditPageRedirect('index')
            ->resourceCreatePageRedirect('index')
            ->navigationGroups([
                'Master Data',
                'Manajemen Toko',
                'Manajemen Produk',
                'Manajemen Adminstrator',

            ])

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                CustomAccountWidget::class,
                // AccountWidget::class,
                // FilamentInfoWidget::class,
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
