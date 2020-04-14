<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductRelationsTablesCreated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('name')->comment('名称');
            $table->unsignedInteger('parent_id')->nullable()->comment('父类目ID');
            $table->foreign('parent_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('cascade');
            $table->unsignedInteger('level')->comment('当前类目层级');
            $table->boolean('is_directory')->comment('是否拥有子类目');
            $table->string('path')->comment('该类目所有父类目ID');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->comment('商品名称');
            $table->text('description')->comment('商品概要');
            $table->text('content')->comment('商品详情');
            $table->text('attribute_list')
                ->comment('用于前端显示，选择后生成product_sku 的sku_sting属性加上product_id查询具体的sku
                 JSON例：{"color":["黑色","白色"],"尺寸"：[“3.5”,"2.8"]')->default('');
            $table->string('image')->comment('商品封面');
            $table->boolean('is_sale')->default(true)->comment('商品是否正在售卖');
            $table->float('rating')->default(5)->comment('商品平均评分');
            $table->unsignedInteger('sold_count')->default(0)->comment('销量');
            $table->unsignedInteger('review_count')->default(0)->comment('评论量');
            $table->decimal('price', 10, 2)->comment('SKU 最低价格');
            $table->timestamps();
        });

        Schema::create('product_sku', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->comment('SKU 名称');
            $table->text('sku_string')
                ->comment('json例： {"color":"黑色","尺寸"：“3.5”，重量："20kg"}');
            $table->unsignedInteger('stock')->comment('库存');
            $table->decimal('price', 10, 2)->comment('SKU 价格');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->timestamps();
            $table->unique(['id','sku_string']);
        });

        Schema::create('product_reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content')->comment('评论内容');
            $table->unsignedTinyInteger('rating')->default(5)->comment('产品的评论星级');
            $table->unsignedInteger('product_id');
            $table->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('product_coupons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('优惠券标题');
            $table->string('code')->unique()->comment('优惠券码，用户下单时输入');
            $table->string('description', 1024)->nullable()->comment('具体描述');
            $table->string('type')->comment('优惠券类型，支持固定金额和百分比折扣');
            $table->decimal('value')->comment('折扣值，根据不同类型含义不同');
            $table->unsignedInteger('total')->comment('全站可兑换的数量');
            $table->unsignedInteger('used')->default(0)->comment('当前已兑换的数量');
            $table->decimal('min_amount', 10, 2)->comment('使用该优惠券的最低订单金额');
            $table->dateTime('start_at')->nullable()->comment('生效开始时间');
            $table->dateTime('expires_at')->nullable()->comment('失效时间');
            $table->boolean('is_active')->comment('优惠券是启用');
            $table->timestamps();
        });
        // 用于构成 属性生成选项，方便管理人员发布商品时候正确的勾选商品属性
        Schema::create('product_sku_attributes_key', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('例：“颜色”，“尺寸”');
            $table->unsignedInteger('sort')->comment('排序');
            $table->timestamps();
        });

        
        Schema::create('product_sku_attributes_value', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sku_attributes_id');
            $table->string('sku_attributes_value');
            $table->foreign('sku_attributes_id')
                ->references('id')
                ->on('product_sku_attributes_key')
                ->onDelete('cascade')
                ->onUpdate('cascade')
            ;
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
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_sku');
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('product_coupons');
        Schema::dropIfExists('product_sku_attributes_key');
        Schema::dropIfExists('product_sku_attributes_value');

    }
}
