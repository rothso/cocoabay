<?php

namespace App;

use Carbon\Carbon;

trait Expires
{
    public static function bootExpires()
    {
        self::creating(function ($model) {
            $model->expires_at = Carbon::now()->addDays(90);
        });
    }

    /**
     * @return bool
     */
    public function getIsExpiredAttribute()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Get the name of the "expires at" column.
     *
     * @return string
     */
    public function getExpiresAtColumn()
    {
        return 'expires_at';
    }
}