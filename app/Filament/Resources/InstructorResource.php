<?php

namespace App\Filament\Resources;

use App\Enums\ApprovalStatus;
use App\Filament\Resources\InstructorResource\Pages;
use App\Filament\Resources\InstructorResource\RelationManagers;
use App\Models\Instructor;
use App\Tables\Columns\AvatarName;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
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

class InstructorResource extends Resource
{
    protected static ?string $model = Instructor::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';

    public static function getNavigationLabel(): string
    {
        return __('main.instructors');
    }

    public static function getPluralLabel(): string
    {
        return __('main.instructors');
    }

    public static function getLabel(): string
    {
        return __('main.instructor');
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
                                Select::make('status')->label(__('main.status'))->options(ApprovalStatus::asSelectArray())->required(),
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
                        RichEditor::make('bio')->label(__('main.bio'))->columnSpan(6)->nullable(),
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
                TextColumn::make('status')->label(__('main.status'))->badge()
                    ->formatStateUsing(fn($state) => ApprovalStatus::getDescription($state))
                    ->color(fn($state) => ApprovalStatus::getColor($state)),
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
            'index' => Pages\ListInstructors::route('/'),
            'create' => Pages\CreateInstructor::route('/create'),
            'edit' => Pages\EditInstructor::route('/{record}/edit'),
        ];
    }
}
