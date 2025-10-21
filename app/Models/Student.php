<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\MediaLibrary\InteractsWithMedia;

class Student extends Authenticatable implements HasMedia
{
    use InteractsWithMedia, HasFactory, HasApiTokens, Notifiable;

    protected $guarded = [];
    protected $hidden = ['password'];

    public function getAvatarAttribute($value)
    {
        return $this->getFirstMediaUrl('avatar') ?: asset('images/default.png');
    }
}
