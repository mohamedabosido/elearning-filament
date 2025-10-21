<?php

namespace App\Models;

class Faq extends TranslationModel
{
    public $translatedAttributes = ['question', 'answer'];
    protected $guarded = [];
    // protected $casts = ['is_active' => IsActive::class];
}
