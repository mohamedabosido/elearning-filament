<?php

namespace App\Filament\Resources;

use App\Filament\Exports\UserExporter;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use App\Tables\Columns\AvatarName;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('main.users');
    }

    public static function getPluralLabel(): string
    {
        return __('main.users');
    }

    public static function getLabel(): string
    {
        return __('main.user');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(6)
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label(__('main.avatar'))
                            ->imageEditor()
                            ->avatar()
                            ->columnSpan(1),
                        Grid::make(2)
                            ->schema([
                                TextInput::make('name')->required()->maxLength(255)->label(__('main.name')),
                                TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true)->label(__('main.email')),
                                PhoneInput::make('phone')->label(__('main.phone'))->required()->unique(ignoreRecord: true)->default('+966'),
                                TextInput::make('password')
                                    ->label(__('main.password'))
                                    ->password()
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->confirmed()
                                    ->maxLength(255)
                                    ->dehydrated(fn($state) => filled($state))
                                    ->revealable(),
                                TextInput::make('password_confirmation')
                                    ->label(__('main.confirm_password'))
                                    ->password()
                                    ->dehydrated(false)
                                    ->required(fn(string $context): bool => $context === 'create')
                                    ->maxLength(255)
                                    ->revealable(),
                                Select::make('roles')->multiple()->relationship('roles', 'name'),
                                Toggle::make('status')->label(__('main.status'))->default(true),
                            ])
                            ->columnSpan(5),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                AvatarName::make('name')->label(__('main.name'))->sortable()->searchable(),
                TextColumn::make('email')->searchable()->label(__('main.email')),
                PhoneColumn::make('phone')->displayFormat(PhoneInputNumberType::INTERNATIONAL)->label(__('main.phone'))->searchable()->copyable(true),
                ToggleColumn::make('status')->label(__('main.status')),
                TextColumn::make('roles.name')->label(__('main.roles'))->badge()->limitList(2),
                TextColumn::make('created_at')->since()->sortable()->label(__('main.created_at')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('main.status'))
                    ->options([
                        true => __('main.active'),
                        false => __('main.inactive'),
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->headerActions([
                ExportAction::make()->exporter(UserExporter::class)->label(__('main.export')),
            ])
            ->modifyQueryUsing(fn ($query) => $query->where('id', '!=', auth_user()->id))
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
                // ExportBulkAction::make()->exporter(AdminExporter::class)->label(__('main.export')),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
