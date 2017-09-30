<?php

namespace App\Listeners;

use App\Events\DriversLicenseSaving;
use Carbon\Carbon;
use Image;
use Intervention\Image\AbstractFont;
use Storage;

class GenerateLicenseImage
{
    /**
     * Handle the event.
     *
     * @param  DriversLicenseSaving $event
     * @return void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle(DriversLicenseSaving $event)
    {
        // The license image will be filled out based on these models
        $license = $event->license;
        $holder = $license->user;

        if(!$license->isDirty())
            return;

        // Load into memory the images we need
        $template = Image::make(Storage::get('templates/drivers_license.png'));
        $headshot = Image::make(Storage::get('templates/devin.png')); // TODO get from db

        // Define all our fonts beforehand to keep the code clean
        $fontHeader = $this->defineFont('AR DECODE Thin', 32);
        $fontLarge = $this->defineFont('Arial Bold', 20);
        $fontMedium = $this->defineFont('Arial Bold', 16);
        $fontSmall = $this->defineFont('Arial Bold', 14);
        $fontSmallRight = $this->defineFont('Arial Bold', 14, '#000000', 'right');
        $fontSmallRightRed = $this->defineFont('Arial Bold', 14, '#9c000f', 'right');
        $fontSignature = $this->defineFont('Arizonia-Regular', 20, '#000000', 'center');

        // Turn the model data into a format we can display on the license
        $number = $license->formatted_number;
        $dob = $license->dob->format('m-d-Y');
        $name = substr(strtoupper($holder->name), 0, 20);
        $address = substr(strtoupper($license->address), 0, 20);
        $sim = substr(strtoupper($license->sim), 0, 20);
        $gender = $license->gender[0]; // get first letter (will be an M or F)
        $height = sprintf('%d-%02d', floor($license->height_in / 12), $license->height_in % 12);
        $weight = $license->weight_lb;
        $hairColor = $license->hairColor->shortcode;
        $eyeColor = $license->eyeColor->shortcode;
        $createdAt = ($license->created_at ?: Carbon::now())->format('m-d-Y');
        $expiresAt = $license->expires_at->format('m-d-Y');
        $signature = substr($holder->name, 0, 20);

        // Actual image processing!
        $image = $template
            ->text('Cocoa Bay, Rockland County', 256, 128, $fontHeader)
            ->insert($headshot, 'top-left', 20, 71)
            ->insert($headshot->resize(75, 75)->opacity(35), 'top-left', 486, 255)
            ->text($number, 282, 150, $fontLarge)
            ->text($dob, 282, 174, $fontLarge)
            ->text($name, 239, 226, $fontMedium)
            ->text($address, 239, 244, $fontMedium)
            ->text($sim, 239, 262, $fontMedium)
            ->text($gender, 527, 146, $fontSmall)
            ->text($height, 527, 167, $fontSmall)
            ->text($weight, 527, 188, $fontSmall)
            ->text($hairColor, 527, 209, $fontSmall)
            ->text($eyeColor, 527, 230, $fontSmall)
            ->text($createdAt, 477, 299, $fontSmallRight)
            ->text($expiresAt, 477, 316, $fontSmallRightRed)
            ->text($signature, 118, 296, $fontSignature);

        // Delete the old image if it exists to prevent orphans from taking up disk space
        if ($license->image != null) {
            Storage::disk('public')->delete($license->image);
        }

        // Use a unique & hard-to-guess name because all images are publicly accessible
        $contents = $image->encode('png')->__toString();
        $fileName = 'license/drivers/' . md5($contents) . '.png';

        // Save for later lookup & retrieval
        $license->image = $fileName;
        Storage::disk('public')->put($fileName, $contents);
    }

    private static function defineFont($resourceName, $size, $color = '#000', $align = 'left')
    {
        return function (AbstractFont $font) use ($resourceName, $size, $color, $align) {
            $font->file(resource_path('font/' . $resourceName . '.ttf'));
            $font->size($size);
            $font->align($align);
            $font->color($color);
        };
    }
}
