<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redx extends Model
{

    protected $guarded = [];
    protected $fillable = ['api_url','token','status','is_active'];
}
