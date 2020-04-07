<?php

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $blogCount = (int)$this->command->ask('How many  orders would you like?', 50);
        $users = App\User::all();

        factory(Order::class,$blogCount)->make()->each(function ($order) use ($users){
            $order->user_id = $users->random()->id;
            $order->save();
        });
    }
}
