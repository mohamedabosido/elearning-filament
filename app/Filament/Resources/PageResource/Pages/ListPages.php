<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\ListTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    use ListTranslatable;
    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
