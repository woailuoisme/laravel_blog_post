<?php

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $commentsCount = max((int)$this->command->ask('How many comments would you like?', 500), 1);
        $users = \App\User::all();
        $posts = Post::all();
        factory(\App\Models\Comment::class, $commentsCount)->make()->each(function (Comment $comment) use( $users,$posts){
            $comment->user_id = $users->random()->id;
            $comment->post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
