<?php

namespace App\Models;

use App\Enums\IsActive;

class Category extends TranslationModel
{
    public $translatedAttributes = ['name'];
    protected $guarded = [];
    // protected $casts = ['is_active' => IsActive::class];

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function getNameAttribute()
    {
        return $this->translate(app()->getLocale())->name;
    }
}
