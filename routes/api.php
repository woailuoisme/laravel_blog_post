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
Route::middleware('jwt.auth')->group(function () {
    Route::get('protected', function () {
        return response()->json([
            'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
        ]);
    });

    Route::middleware('jwt.refresh')->get('refresh',
        function () {
            return response()->json([
                'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
            ]);
        }
    );
});



Route::prefix('v1')->group(function () {

    Route::prefix('auth')->namespace('Auth')->group(function ($router) {
        Route::post('login', 'LoginController@login');
        Route::post('logout', 'LogoutController@logout');
        Route::get('me', 'UserController@me');
        Route::post('signup', 'SignUpController@signUp');
        Route::post('recovery', 'ForgotPasswordController@sendResetEmail');
        Route::post('reset', 'ResetPasswordController@resetPassword');
        Route::post('refresh', 'RefreshController@refresh');
    });
    // Controllers Within The "App\Http\Controllers\Admin" Namespace
    route::prefix('cart')->group(function () {
        Route::get('', 'CartAPIController@index')->name('cart.index');
        Route::post('add', 'CartAPIController@addToCart')->name('cart.add');
        Route::post('checkout', 'CartAPIController@checkout')->name('cart.checkout');
        Route::put('quantity', 'CartAPIController@updateProductQuantity')->name('cart.quantity');
        Route::post('clear', 'CartAPIController@clearCart')->name('cart.clear');
    });
    Route::apiResource('orders', 'OrderAPIController');
//    Route::prefix('user')->group(function (){
//        Route::apiResource('profiles', 'ProfileAPIController');
//    });
    Route::apiResource('transactions', 'TransactionAPIController');
    Route::apiResource('profiles', 'ProfileAPIController');
    Route::apiResource('posts', 'PostAPIController');
    Route::apiResource('categories', 'CategoryAPIController');
    Route::apiResource('tags', 'TagAPIController');
//    Route::apiResource('comments', 'CommentAPIController');
    Route::apiResource('posts.comments', 'PostCommentController');
    Route::apiResource('products', 'ProductAPIController');

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








