<?php

use Illuminate\Database\Seeder;

class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cartCount = (int)$this->command->ask('How many  orders would you like?', 50);
        $users = App\User::all();

        factory(\App\Models\Cart::class,$cartCount)->make()->each(function ($cart) use ($users){
            $cart->user_id = $users->random()->id;
            $cart->save();
        });
    }
}
