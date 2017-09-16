<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DriversLicense extends Model
{
    protected $appends = ['is_expired'];
    protected $dates = [
        'created_at',
        'updated_at',
        'expiry'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getIsExpiredAttribute()
    {
        return Carbon::now()->greaterThan($this->expiry);
    }
}
