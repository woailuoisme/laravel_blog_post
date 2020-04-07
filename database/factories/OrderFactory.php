<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Cart;
use Faker\Generator as Faker;

$factory->define(\App\Models\Order::class, function (Faker $faker) {

    return [
        'statusCode' =>$faker->randomElement(['created','pending','processing','completed','failed']),
        'order_num' =>$faker->uuid,
        'total_price' =>$faker->randomFloat(2,1000,10000),
        'created_at' => $faker->dateTimeBetween('-3 months'),
        'updated_at' => $faker->dateTimeBetween('-1 months'),
    ];
});
