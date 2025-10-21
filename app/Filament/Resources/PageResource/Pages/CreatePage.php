<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use CactusGalaxy\FilamentAstrotomic\Resources\Pages\Record\CreateTranslatable;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use CreateTranslatable;
    protected static string $resource = PageResource::class;
}
