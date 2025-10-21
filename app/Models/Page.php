<?php

namespace App\Models;


class Page extends TranslationModel
{
    public $translatedAttributes = ['title', 'content'];
    protected $guarded = [];
    // protected $casts = ['is_active' => IsActive::class];
}
