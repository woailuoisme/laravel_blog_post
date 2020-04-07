<?php

use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $usersCount = max((int)$this->command->ask('How many product would you like?', 20), 1);
        factory(\App\Models\Product::class, $usersCount)->create();
    }
}
