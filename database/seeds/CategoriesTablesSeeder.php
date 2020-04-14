<?php

use Illuminate\Database\Seeder;

class CategoriesTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create(config('app.faker_locale'));
        $categoriesCount = max((int)$this->command->ask('How many categories would you like?', 20), 1);
        $categories = collect(['Science', 'Sport', 'Politics', 'Entartainment', 'Economy']);
//        factory(\App\Models\Category::class, $categoriesCount)->create();
        $categories->each(function ($category) use ($faker) {
            \App\Models\Category::create(
                [
                    'name' => $faker->word(),
                    'hot' => random_int(1000, 9999),
                    
                    'image' => $faker->imageUrl(),
                    'created_at' => $faker->dateTimeBetween('-3 months'),
                    'updated_at' => $faker->dateTimeBetween('-1 months'),
                ]
            );
        });
    }
}
