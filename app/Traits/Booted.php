<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Route;

trait Booted
{
  public static function booted() {
    static::creating(function ($model) {
      $model->created_by = optional(auth_user())->id;
    });
    static::updating(function ($model) {
      $model->updated_by = optional(auth_user())->id;
    });
  }

  public function created_by_user()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

}
