<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class LicensePlate extends Model
{
    use Expires;

    protected $fillable = [
        'style_id', 'tag', 'make', 'model', 'class', 'color', 'year'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'expires_at'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        self::creating(function ($plate) {
            $plate->tag = $plate->tag ?: strtoupper(str_random(8));
        });

        // TODO: move to event handler class
        $drawPlate = function ($plate) {
            // TODO: generate real license plate image
            $image = \Illuminate\Http\UploadedFile::fake()
                ->image('test.png', random_int(1, 100), random_int(1, 100))
                ->store('license/plate', 'public');

            if ($plate->image != null) {
                Storage::disk('public')->delete($plate->image);
            }

            $plate->image = $image;
        };

        self::creating($drawPlate);
        self::updating($drawPlate);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}