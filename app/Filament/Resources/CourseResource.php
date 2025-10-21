<?php

namespace App\Filament\Resources;

use App\Enums\ApprovalStatus;
use App\Enums\CourseLevel;
use App\Filament\Resources\CourseResource\Pages;
use App\Filament\Resources\CourseResource\RelationManagers;
use App\Models\Category;
use App\Models\Course;
use App\Models\Language;
use App\Tables\Columns\PriceColumn;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Course::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function getNavigationLabel(): string
    {
        return __('main.courses');
    }

    public static function getPluralLabel(): string
    {
        return __('main.courses');
    }

    public static function getLabel(): string
    {
        return __('main.course');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', ApprovalStatus::WaitingForApproval)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Courses')->tabs([
                    Tab::make('Basic info')
                        ->label(__('main.basic_info'))
                        ->icon('heroicon-o-language')
                        ->schema([
                            TranslatableTabs::make()
                                ->localeTabSchema(fn(TranslatableTab $tab) => [
                                    TextInput::make($tab->makeName('title'))
                                        ->required()
                                        ->maxLength(255)
                                        ->label(__('main.title'))
                                        ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                    RichEditor::make($tab->makeName('description'))
                                        ->required()
                                        ->label(__('main.description')),
                                    TextInput::make($tab->makeName('duration'))
                                        ->required()
                                        ->maxLength(255)
                                        ->label(__('main.duration')),
                                ])->columnSpan(2),
                        ]),
                    Tab::make('Additional info')
                        ->label(__('main.additional_info'))
                        ->icon('heroicon-o-information-circle')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('thumbnail')
                                ->label(__('main.thumbnail'))
                                ->image()
                                ->imageEditor()
                                ->required(),
                            Grid::make(2)->schema([
                                Hidden::make('slug'),
                                TextInput::make('price')
                                    ->label(__('main.price'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->gt('discount_price')
                                    ->required(),
                                TextInput::make('discount_price')
                                    ->label(__('main.discount_price'))
                                    ->numeric()
                                    ->minValue(0)
                                    ->lt('price')
                                    ->nullable(),
                                Select::make('status')
                                    ->label(__('main.status'))
                                    ->options(ApprovalStatus::asSelectArray())
                                    ->default(ApprovalStatus::WaitingForApproval()->value)
                                    ->required(),
                                Select::make('level')
                                    ->label(__('main.level'))
                                    ->options(CourseLevel::asSelectArray())
                                    ->default(CourseLevel::Beginner()->value)
                                    ->required(),
                            ])
                        ]),
                    Tab::make('Relations')
                        ->label(__('main.relations'))
                        ->icon('heroicon-o-link')
                        ->schema([
                            Grid::make(2)->schema([
                                Select::make('category_id')
                                    ->label(__('main.category'))
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Select::make('language_id')
                                    ->label(__('main.language'))
                                    ->options(Language::all()->pluck('name', 'id'))
                                    ->searchable()
                                    ->required(),
                                Select::make('instructor_id')
                                    ->label(__('main.instructor'))
                                    ->relationship('instructor', 'name')
                                    ->searchable()
                                    ->required(),
                            ])
                        ])
                ])
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.title')->label(__('main.title')),
                TextColumn::make('status')->label(__('main.status'))->badge()
                    ->formatStateUsing(fn($state) => ApprovalStatus::getDescription($state))
                    ->color(fn($state) => ApprovalStatus::getColor($state)),
                TextColumn::make('category.translation.name')->label(__('main.category'))->badge()->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('language.translation.name')->label(__('main.language'))->badge()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('level')->label(__('main.level'))->badge()
                    ->formatStateUsing(fn($state) => CourseLevel::getDescription($state))
                    ->color(fn($state) => CourseLevel::getColor($state))->toggleable(isToggledHiddenByDefault: true),
                PriceColumn::make('price')->label(__('main.price'))->sortable(),
                TextColumn::make('instructor.name')->label(__('main.instructor')),
                TextColumn::make('created_at')->since()->sortable()->label(__('main.created_at')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('delete')
                    ->label(__('main.delete'))
                    ->icon('heroicon-s-trash')
                    ->action(fn($record) => $record->delete())
                    ->color('danger')
                    ->requiresConfirmation()
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
            'index' => Pages\ListCourses::route('/'),
            'create' => Pages\CreateCourse::route('/create'),
            'edit' => Pages\EditCourse::route('/{record}/edit'),
        ];
    }
}
