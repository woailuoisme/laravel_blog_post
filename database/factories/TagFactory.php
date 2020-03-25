<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Models\Tag;
use Faker\Generator as Faker;

$factory->define(App\Models\Tag::class, function (Faker $faker) {
    $tags = collect(['Science', 'Sport', 'Politics', 'Entartainment', 'Economy']);

    $tags->each(function ($tagName) {
        $tag = new Tag();
        $tag->name = $tagName;
        $tag->hot = random_int(1000,9999);
        $tag->save();
    });
});
