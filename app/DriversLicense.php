<?php

namespace App;

use App\Events\DriversLicenseSaving;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DriversLicense extends Model
{
    protected $fillable = [
        'dob', 'gender', 'height_in', 'weight_lb', 'eye_color_id', 'hair_color_id', 'address', 'sim'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'expires_at'
    ];

    protected $appends = [
        'is_expired'
    ];

    protected $events = [
        'saving' => DriversLicenseSaving::class
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function eyeColor() {
        return $this->belongsTo('App\EyeColor');
    }

    public function hairColor() {
        return $this->belongsTo('App\HairColor');
    }

    /**
     * @param $dob
     * @return Carbon
     */
    public function getDobAttribute($dob) {
        return Carbon::parse($dob);
    }

    /**
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * @return string
     */
    public function getFormattedNumberAttribute() {
        return join('-', str_split($this->number, 3));
    }
}
