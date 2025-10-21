<?php

namespace App\Models;

use App\Enums\ApprovalStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Course extends TranslationModel implements HasMedia
{
    use InteractsWithMedia, HasFactory;

    public $translatedAttributes = ['title', 'description', 'duration'];
    protected $casts = ['status' => ApprovalStatus::class];
    protected $guarded = [];

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function getThumbnailAttribute($value)
    {
        return $this->getFirstMediaUrl('thumbnail') ?: asset('images/default.png');
    }

    public function getNameAttribute()
    {
        return $this->translate(app()->getLocale())->name;
    }

}
