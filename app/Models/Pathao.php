<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pathao extends Model
{

    protected $guarded = [];
    protected $fillable = ['api_url','api_key','api_secret','api_email','api_password','api_token','status','is_active','refresh_token'];
}
