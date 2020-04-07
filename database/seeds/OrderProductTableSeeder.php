<?php

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $productCount = Product::all()->count();
        if (0 === $productCount) {
            $this->command->info('No product found, skipping assigning tags to  posts');
            return;
        }
        \App\Models\Cart::all()->each(function (\App\Models\Cart $order) use($productCount) {
            $take = random_int(0, $productCount);
            $orders = Product::inRandomOrder()->take($take)->get()->pluck('id');
            foreach ($orders as $key => $id)
            {
                $order->products()->attach($id, ['quantity'=>random_int(1,5)]);
            }
//                $order->products()->sync($order,['quantity'=>random_int(1,5)]);
        });
    }
}
