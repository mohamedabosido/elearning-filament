<?php

namespace App\Providers\Filament;

use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use App\Enums\ApprovalStatus;
use App\Http\Middleware\EnsureModelIsActive;
use App\Models\Course;
use App\Models\Instructor;
use CactusGalaxy\FilamentAstrotomic\FilamentAstrotomicTranslatablePlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->passwordReset()
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Indigo,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            // ->topNavigation()
            ->databaseNotifications()
            ->brandLogo(site_settings('logo'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('images/logo.png'))
            ->brandName(site_settings('name'))
            ->font('IBM Plex Sans Arabic')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->plugins([
                FilamentAstrotomicTranslatablePlugin::make(),
                FilamentSpatieRolesPermissionsPlugin::make(),
                FilamentEditProfilePlugin::make()->shouldShowAvatarForm(
                    value: true,
                    directory: 'avatars',
                    rules: 'mimes:jpeg,png|max:1024'
                ),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => auth_user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
            ])
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder
                    ->items([
                        NavigationItem::make('Dashboard')
                            ->label(__('main.dashboard'))
                            ->icon('heroicon-o-home')
                            ->isActiveWhen(fn() => request()->routeIs('filament.admin.pages.dashboard'))
                            ->url(route('filament.admin.pages.dashboard')),
                    ])
                    ->groups([
                        NavigationGroup::make('System Constants')
                            ->label(__('main.system_constants'))
                            ->icon('heroicon-o-adjustments-horizontal')
                            ->items([
                                NavigationItem::make('Categories')
                                    ->label(__('main.categories'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.categories.*'))
                                    ->url(route('filament.admin.resources.categories.index')),
                                NavigationItem::make('Languages')
                                    ->label(__('main.languages'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.languages.*'))
                                    ->url(route('filament.admin.resources.languages.index')),
                                NavigationItem::make('faqs')
                                    ->label(__('main.faqs'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.faqs.*'))
                                    ->url(route('filament.admin.resources.faqs.index')),
                                NavigationItem::make('pages')
                                    ->label(__('main.pages'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.pages.*'))
                                    ->url(route('filament.admin.resources.pages.index')),
                            ]),
                        NavigationGroup::make('Instructors & Courses')
                            ->label(__('main.instructors_and_courses'))
                            ->icon('heroicon-o-academic-cap')
                            ->items([
                                NavigationItem::make('Instructors')
                                    ->label(__('main.instructors'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.instructors.*'))
                                    ->badge(fn() => Instructor::where('status', ApprovalStatus::WaitingForApproval)->count() ?: null)
                                    ->url(route('filament.admin.resources.instructors.index')),
                                NavigationItem::make('Students')
                                    ->label(__('main.students'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.students.*'))
                                    ->url(route('filament.admin.resources.students.index')),
                                NavigationItem::make('Courses')
                                    ->label(__('main.courses'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.courses.*'))
                                    ->badge(fn() => Course::where('status', ApprovalStatus::WaitingForApproval)->count() ?: null)
                                    ->url(route('filament.admin.resources.courses.index')),
                            ]),
                        NavigationGroup::make('Users & Roles')
                            ->label(__('main.users_and_roles'))
                            ->icon('heroicon-o-users')
                            ->items([
                                NavigationItem::make('Users')
                                    ->label(__('main.users'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.users.*'))
                                    ->url(route('filament.admin.resources.users.index')),
                                NavigationItem::make('Roles')
                                    ->label(__('main.roles'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.roles.*'))
                                    ->url(route('filament.admin.resources.roles.index'))
                            ]),
                        NavigationGroup::make('Site Settings')
                            ->label(__('main.settings'))
                            ->icon('heroicon-o-cog')
                            ->items([
                                NavigationItem::make('Settings')
                                    ->label(__('main.site_settings'))
                                    ->isActiveWhen(fn() => request()->routeIs('filament.admin.resources.site-settings.*'))
                                    ->url(route('filament.admin.resources.site-settings.edit', ['record' => 1])),
                            ]),
                    ]);
            })
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
                EnsureModelIsActive::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
