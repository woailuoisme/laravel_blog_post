<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogCount = (int)$this->command->ask('How many  posts would you like?', 50);
        $users = App\User::all();
        $categories = \App\Models\Category::all();

        factory(\App\Models\Post::class,$blogCount)->make()->each(function ($post) use ($users,$categories){
            $post->user_id = $users->random()->id;
            $post->category_id = $categories->random()->id;
            $post->save();
        });
    }
}
