<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if ($this->command->confirm('Do you want to fresh the database?',true)) {
            $this->command->call('migrate:fresh');
            $this->command->error('Database was freshed');
        }
//        Cache::tags(['blog-post'])->flush();
        $this->call([
//            UsersTableSeeder::class,
//            CategoriesTablesSeeder::class,
//            PostsTableSeeder::class,
//            CommentsTableSeeder::class,
//            TagsTableSeeder::class,
//            PostTagTableSeeder::class,
//
            ProductsTableSeeder::class,
//            OrdersTableSeeder::class,
//            CartsTableSeeder::class,
//            OrderProductTableSeeder::class,
//            CartProductTableSeeder::class
        ]);
    }
}
