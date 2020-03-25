<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->string('title')->comment('标题');
//            $table->string('slug')->unique()->index()->comment('锚点');
            $table->text('content')->comment('内容');
            $table->integer('view_count')->unsigned()->comment('浏览次数');
            $table->boolean('is_published')->comment('文章是否发布');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::dropIfExists('posts');
    }
}
