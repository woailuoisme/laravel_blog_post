<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {

    return [
        'name' => $faker->word(2),
        'description' => $faker->paragraphs(2, true),
        'content' => $faker->paragraphs(5, true),
        'imageURL' => $faker->imageUrl(),
        'sku' => random_int(1000,9999),
        'discount' => $faker->randomFloat(3,0.8,1),
        'price' => $faker->randomFloat(3,100,1100),
        'created_at' => $faker->dateTimeBetween('-3 months'),
        'updated_at' => $faker->dateTimeBetween('-1 months'),
    ];
});
