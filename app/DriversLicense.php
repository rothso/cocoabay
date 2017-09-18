<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DriversLicense extends Model
{
    protected $fillable = [
        'dob', 'gender', 'height_in', 'weight_lb', 'eye_color_id', 'hair_color_id', 'address'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'expires_at'
    ];

    protected $appends = [
        'is_expired'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getIsExpiredAttribute()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }
}
