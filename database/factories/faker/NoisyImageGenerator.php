<?php

use Faker\Provider\Image;
use Illuminate\Http\Testing\File;

class NoisyImageGenerator extends Image
{

    public static function noisyImage($width = 640, $height = 480)
    {
        if (!extension_loaded('gd')) {
            throw new \Exception('GD extension is required to generate image');
        }

        $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        return new File($name . '.png', self::generateNoisyImage($width, $height));
    }

    protected static function generateNoisyImage($width, $height)
    {
        return tap(tmpfile(), function ($temp) use ($width, $height) {
            ob_start();

            $image = imagecreatetruecolor($width, $height);
            $black = imagecolorallocate($image, 0, 0, 0);
            $white = imagecolorallocate($image, 255, 255, 255);

            for ($w = 0; $w < $width; $w++) {
                for ($h = 0; $h < $height; $h++) {
                    imagesetpixel($image, $w, $h, mt_rand(0, 1) ? $white : $black);
                }
            }

            header('Content-Type: image/png');
            imagepng($image);

            fwrite($temp, ob_get_clean());
        });
    }
}