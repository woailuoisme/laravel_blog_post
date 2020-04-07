<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cart;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {

    return [
        'created_at' => $faker->dateTimeBetween('-3 months'),
        'updated_at' => $faker->dateTimeBetween('-1 months'),
    ];
});
