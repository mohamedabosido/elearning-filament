<?php

namespace App\Models;

use App\Models\Model;
use App\Traits\HasTranslationModel;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;

class TranslationModel extends Model implements TranslatableContract
{
    use HasFactory;
    use Translatable;
    use HasTranslationModel;
}
