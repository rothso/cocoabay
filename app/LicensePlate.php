<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LicensePlate extends Model
{
    protected $fillable = [
        'style_id', 'tag', 'make', 'model', 'class', 'color', 'year'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
