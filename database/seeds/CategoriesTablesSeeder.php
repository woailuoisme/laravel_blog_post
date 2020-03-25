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
        $categoriesCount = max((int)$this->command->ask('How many categories would you like?', 20), 1);
        factory(\App\Models\Category::class, $categoriesCount)->create();
    }
}
