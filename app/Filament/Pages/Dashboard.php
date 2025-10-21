<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;

    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('main.filter_by_date'))
                    ->schema([
                        DatePicker::make('start_date')->label(__('main.start_date')),
                        DatePicker::make('end_date')->label(__('main.end_date')),
                    ])->columns(2)->collapsible(),
            ]);
    }
}
