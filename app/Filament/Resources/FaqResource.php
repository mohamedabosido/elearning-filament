<?php

namespace App\Filament\Resources;

use App\Enums\IsActive;
use App\Filament\Resources\FaqResource\Pages;
use App\Filament\Resources\FaqResource\RelationManagers;
use App\Models\Faq;
use CactusGalaxy\FilamentAstrotomic\Forms\Components\TranslatableTabs;
use CactusGalaxy\FilamentAstrotomic\Resources\Concerns\ResourceTranslatable;
use CactusGalaxy\FilamentAstrotomic\TranslatableTab;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FaqResource extends Resource
{
    use ResourceTranslatable;

    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationLabel(): string
    {
        return __('main.faqs');
    }

    public static function getPluralLabel(): string
    {
        return __('main.faqs');
    }

    public static function getLabel(): string
    {
        return __('main.faq');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TranslatableTabs::make()
                    ->localeTabSchema(fn(TranslatableTab $tab) => [
                        TextInput::make($tab->makeName('question'))
                            ->required()
                            ->maxLength(255)
                            ->label(__('main.question')),
                        RichEditor::make($tab->makeName('answer'))
                            ->required()
                            ->label(__('main.answer')),
                    ])->columnSpan(2),
                Toggle::make('is_active')->label(__('main.status')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.question')->label(__('main.question')),
                TextColumn::make('is_active')->label(__('main.status'))->badge()
                    ->formatStateUsing(fn($state) => IsActive::getDescription($state))
                    ->color(fn($state) => IsActive::getColor($state)),
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
                ->requiresConfirmation(),
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
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
