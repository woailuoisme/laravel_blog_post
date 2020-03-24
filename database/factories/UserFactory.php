
<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    $createdTime = $faker->dateTimeBetween('-3 months');
    $updatedTime =(clone $createdTime)->modify('+5 days');
    $emailVerifiedTime =(clone $createdTime)->modify('+5 minutes');
    return [
        'name' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => $emailVerifiedTime,
        'password' => bcrypt('1233456'), // password
        'remember_token' => Str::random(10),
        'created_at' => $createdTime,
        'updated_at' => $updatedTime,
    ];
});



$factory->state(User::class, 'user-one', function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
    ];
});

$factory->afterCreating(App\User::class, function ($user, $faker) {
    $user->profile()->save(factory(\App\Models\Profile::class)->make());
});
