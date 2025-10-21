<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Filament\Resources\StudentResource\RelationManagers;
use App\Models\Student;
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
use Ysfkaya\FilamentPhoneInput\PhoneInputNumberType;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';


    public static function getNavigationLabel(): string
    {
        return __('main.students');
    }

    public static function getPluralLabel(): string
    {
        return __('main.students');
    }

    public static function getLabel(): string
    {
        return __('main.student');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(6)
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('avatar')
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
                            ])->columnSpan(5),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label(__('main.name'))->sortable()->searchable(),
                TextColumn::make('email')->searchable()->label(__('main.email')),
                PhoneColumn::make('phone')->displayFormat(PhoneInputNumberType::INTERNATIONAL)->label(__('main.phone'))->searchable()->copyable(true),
                TextColumn::make('created_at')->since()->sortable()->label(__('main.created_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
