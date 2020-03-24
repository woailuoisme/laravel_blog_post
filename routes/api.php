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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('posts', 'PostAPIController');

Route::apiResource('comments', 'CommentAPIController');

Route::apiResource('posts', 'PostAPIController');

Route::apiResource('categories', 'CategoryAPIController');

Route::apiResource('tags', 'TagAPIController');

Route::apiResource('profiles', 'ProfileAPIController');
Route::apiResource('posts.comments','PostCommentController');

Route::prefix('v2')->name('api.v2')->namespace('api\v2')->group(function () {
    Route::get('/status', function () {
        return response()->json(['status' => true]);
    });
});

Route::fallback(function () {
    return response()->json([
        'message' => 'Not found'
    ], 404);
})->name('api.fallback');






