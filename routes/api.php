<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    Route::prefix('auth')->group(function ($router) {
        Route::post('login', 'AuthController@login');
        Route::post('logout', 'AuthController@logout');
        Route::get('me', 'AuthController@me');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('signup', 'AuthController@signUp');

        Route::post('password/forget', 'AuthController@forgetPassword');
        Route::post('password/reset', 'AuthController@resetPassword');

//        Route::post('recovery', 'ForgotPasswordController@sendResetEmail');
//        Route::post('reset', 'ResetPasswordController@resetPassword');

//        Route::post('password/email', 'Auth\ForgotPasswordController@getResetToken');
//        Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    });
    // Controllers Within The "App\Http\Controllers\Admin" Namespace

    route::prefix('user')->group(function (){
        route::prefix('cart')->group(function () {
            Route::get('', 'CartController@index')->name('user.cart.index');
            Route::post('add', 'CartController@addProductToCart')->name('user.cart.add');
            Route::post('checkout', 'CartController@checkout')->name('user.cart.checkout');
            Route::put('quantity', 'CartController@updateProductQuantity')->name('user.cart.quantity');
            Route::delete('remove_single', 'CartController@removeSingle')->name('user.cart.remove.single');
            Route::delete('remove_multi', 'CartController@removeMulti')->name('user.cart.remove.multi');
            Route::delete('clear', 'CartController@clearCart')->name('user.cart.clear');
        });
        route::prefix('order')->group(function (){
            Route::get('', 'OrderController@index')->name('cart.list');
            Route::get('/{id}', 'OrderController@show')->name('cart.detail');
            Route::post('pay','OrderController@pay')->name('cart.pay');
            Route::delete('/{id}','OrderController@cancleOrder')->name('cart.cancel');
            Route::delete('','OrderController@cancleMultiOrder')->name('cart.cancel.multi');
        });
        route::prefix('profile')->group(function (){
            Route::get('','UserProfileController@index')->name('user.profile.index');
            Route::post('','UserProfileController@store')->name('user.profile.create');
            //必须使用post 方式上传，put patch 都会获取不到文件
            Route::post('update','UserProfileController@update')->name('user.profile.update');
            Route::get('','UserProfileController@show')->name('user.profile.show');
        });
        route::get('favorite_products','UserController@userFavoriteProducts')->name('user.favorite.list');
        route::post('favorite_product','UserController@favorite')->name('user.favorite');
        route::post('cancel_favorite_product','UserController@cancelFavorite')->name('user.cancel.favorite');
        route::post('like_product','UserController@upProduct')->name('user.like.product');
        route::post('unlike_product','UserController@downProduct')->name('user.unlike.product');

    });
    route::prefix('products')->group(function (){
        route::get('','ProductController@index');
        route::get('/{id}','ProductController@show');
//        route::get('/show','ProductController@show');
    });

    route::prefix('admin')->group(function (){
        Route::apiResource('posts', 'PostAPIController');
        Route::apiResource('categories', 'CategoryAPIController');
        Route::apiResource('tags', 'TagAPIController');
//    Route::apiResource('comments', 'CommentAPIController');
        Route::apiResource('posts.comments', 'PostCommentController');
        Route::apiResource('products', 'ProductAPIController');
        Route::apiResource('orders', 'OrderAPIController');
    });
    Route::get('/test', function () {
        return response()->json([
            'success'=>false,
        ]);
    });

});

Route::get('/doc', function () {
//    return
});

//Route::prefix('v2')->name('api.v2')->namespace('api\v2')->group(function () {
//    Route::get('/status', function () {
//        return response()->json(['status' => true]);
//    });
//});

Route::fallback(function () {
    return response()->json([
        'message' => \request()->url() .' Not found'
    ], 404);
})->name('api.fallback');








