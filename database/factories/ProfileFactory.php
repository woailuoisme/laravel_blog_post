<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Profile;
use Faker\Generator as Faker;

$factory->define(Profile::class, function (Faker $faker) {

    return [
        'avatar' => $faker->imageUrl(120,120),
        'created_at' => $faker->dateTimeBetween('-3 months'),
        'updated_at' => $faker->dateTimeBetween('-1 months'),
    ];
});
