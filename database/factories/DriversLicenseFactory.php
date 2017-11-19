<?php

use Faker\Generator as Faker;
use App\Helpers\Faker\NoisyImageGenerator;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\DriversLicense::class, function (Faker $faker) {
    $faker->addProvider(new NoisyImageGenerator($faker));

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
        'photo' => $faker->noisyImage(100, 100)->store('license/photo', 'public'),
        'expires_at' => $faker->dateTimeBetween('now', '90 days'),
    ];
});
