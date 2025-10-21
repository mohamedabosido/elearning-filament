<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Filament\Resources\SiteSettingResource\RelationManagers;
use App\Models\SiteSetting;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('main.site_settings');
    }

    public static function getPluralLabel(): string
    {
        return __('main.site_settings');
    }

    public static function getLabel(): string
    {
        return __('main.site_setting');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(4)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->label(__('main.logo'))
                            ->image()
                            ->imageEditor()
                            ->nullable()
                            ->columnSpan(1),
                        TranslatableTabs::make()
                            ->localeTabSchema(fn(TranslatableTab $tab) => [
                                TextInput::make($tab->makeName('name'))
                                    ->required()
                                    ->maxLength(255)
                                    ->label(__('main.name')),
                                TextInput::make($tab->makeName('address'))
                                    ->required()
                                    ->label(__('main.address')),
                            ])->columnSpan(3)
                    ]),
                Grid::make(2)
                    ->schema([
                        TextInput::make('email')->label(__('main.email'))->email()->required(),
                        PhoneInput::make('phone')->label(__('main.phone'))->required(),
                        PhoneInput::make('whatsapp')->label(__('main.whatsapp'))->required(),
                        TextInput::make('facebook')->label(__('main.facebook'))->url()->nullable(),
                        TextInput::make('youtube')->label(__('main.youtube'))->url()->nullable(),
                        TextInput::make('twitter')->label(__('main.twitter'))->url()->nullable(),
                        TextInput::make('instagram')->label(__('main.instagram'))->url()->nullable(),
                        TextInput::make('linkedin')->label(__('main.linkedin'))->url()->nullable(),
                        TextInput::make('tiktok')->label(__('main.tiktok'))->url()->nullable(),
                    ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.name')->label(__('main.site_setting')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'create' => Pages\CreateSiteSetting::route('/create'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }
}
