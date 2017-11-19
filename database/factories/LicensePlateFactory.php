<?php

use Faker\Generator as Faker;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\LicensePlateStyle::class, function (Faker $faker) {
    $faker->addProvider(new NoisyImageGenerator($faker));

    return [
        'name' => $faker->unique()->word,
        'image' => $faker->noisyImage(200, 50)->store('license/plate/styles', 'public'),
    ];
});

$factory->define(App\LicensePlate::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\User::class)->create()->id;
        },
        'style_id' => function () {
            return factory(App\LicensePlateStyle::class)->create()->id;
        },
        'make' => 'Toyota',
        'model' => 'Camry',
        'class' => 'Sedan',
        'color' => $faker->colorName,
        'year' => $faker->year,
    ];
});
