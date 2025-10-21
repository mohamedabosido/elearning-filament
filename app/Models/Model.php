<?php

namespace App\Models;

use App\Traits\Booted;
use App\Traits\HasBaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
  use Booted;
  use SoftDeletes;
  use HasBaseModel;
//   public $preventsLazyLoading = true;

}
