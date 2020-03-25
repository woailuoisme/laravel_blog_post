<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {

    return [
        'title' => $faker->sentence(10),
        'content' => $faker->paragraphs(5, true),
        'view_count' => random_int(1000,9999),
        'is_published' => $faker->boolean(),
        'created_at' => $faker->dateTimeBetween('-3 months'),
        'updated_at' => $faker->dateTimeBetween('-1 months'),
    ];
});
