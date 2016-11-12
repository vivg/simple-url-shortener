<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\ShortUrl::class, function (Faker\Generator $faker) {

    return [
        'short_code' => str_random(5)
    ];
});


/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Models\DeviceUrl::class, function (Faker\Generator $faker) {

    return [
        'short_code' => null,
        'device_type' => 'desktop',
        'long_url' => $faker->url,
    ];
});


