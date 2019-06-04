<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{

    public function owner()
    {
        return $this->belongsTo("App\Models\User");
    }
    protected $fillable = ['user_id', 'phone', 'address', 'city', 'zipcode'];
}
