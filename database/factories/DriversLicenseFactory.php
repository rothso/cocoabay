<?php

use Faker\Generator as Faker;
use Illuminate\Http\UploadedFile;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\DriversLicense::class, function (Faker $faker) {
    // Each photo will be a different size to ensure the hashes are unique
    $dimen =  $faker->unique()->numberBetween(0, 200);

    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'number' => $faker->randomNumber(9),
        'dob' => $faker->date(),
        'gender' => $faker->randomElement(['MALE', 'FEMALE']),
        'height_in' => $faker->randomNumber(2),
        'weight_lb' => $faker->randomNumber(3),
        'eye_color_id' => $faker->randomElement(\App\EyeColor::pluck('id')->all()),
        'hair_color_id' => $faker->randomElement(\App\HairColor::pluck('id')->all()),
        'address' => $faker->streetAddress,
        'sim' => $faker->city,
        'photo' => UploadedFile::fake()->image('test.png', $dimen, $dimen)->store('license/photo', 'public'),
        'expires_at' => $faker->dateTimeBetween('now', '90 days'),
    ];
});
