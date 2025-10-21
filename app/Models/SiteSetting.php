<?php

namespace App\Models;

use App\Events\SiteSettingCache;
use App\Traits\Booted;
use App\Traits\HasBaseModel;
use Astrotomic\Translatable\Translatable;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class SiteSetting extends Model implements HasMedia, TranslatableContract
{
    use InteractsWithMedia, Translatable, HasFactory, Booted, HasBaseModel;

    public $translatedAttributes = ['name', 'address'];
    protected $guarded = [];

    protected $dispatchesEvents = [
        'created' => SiteSettingCache::class,
        'updated' => SiteSettingCache::class,
        'deleted' => SiteSettingCache::class,
    ];

    public function getLogoAttribute($value)
    {
        return $this->getFirstMediaUrl('logo');
    }

}
