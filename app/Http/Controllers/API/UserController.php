<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\AppBaseController;
use App\Models\Product;
use App\User;
use Illuminate\Http\Request;

class UserController extends AppBaseController
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function favorite(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate_data = $request->validate([
            'product_id'=> ['required','integer']
        ]);
        $product_id = $validate_data['product_id'];
        $product = Product::find($product_id);
        if (empty($product)) {
            return $this->sendError("product $product_id is not exists");
        }
        /** @var User $user */
        $user = auth('api')->user();
        if ($user->existsFavoriteProduct($product_id)) {
            return $this->sendError('product has been favorite');
        }
        $user->favoriteProducts()->attach($product_id);
        return $this->sendSuccess("user {$user->name} favorite {$product_id} ");
    }

    public function cancelFavorite(Request $request): \Illuminate\Http\JsonResponse
    {
        $validate_data = $request->validate([
            'product_id'=> ['required','integer']
        ]);
        $product_id = $validate_data['product_id'];
        $product = Product::find($product_id);
        if (empty($product)) {
            return $this->sendError("product $product_id is not exists");
        }
        /** @var User $user */
        $user = auth('api')->user();
        if (!$user->existsFavoriteProduct($product_id)) {
            return $this->sendError('product is\'t be favorite');
        }
        $user->favoriteProducts()->attach($product_id);
        return $this->sendSuccess("user {$user->name} cancel favorite {$product_id} ");
    }

    public function likeProduct(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $validate_data = $request->validate([
            'product_id'=> ['required','integer']
        ]);
        $product_id = $validate_data['product_id'];
        $product = Product::find($product_id);
        if (empty($product)) {
            return $this->sendError("product $product_id is not exists");
        }
        /** @var User $user */
        $user = auth('api')->user();
        $user->likeProduct($product_id, User::TYPE_LIKE);
        return $this->sendSuccess('You liked successfully product');
    }

    public function unlikeProduct(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $validate_data = $request->validate([
            'product_id'=> ['required','integer']
        ]);
        $product_id = $validate_data['product_id'];
        $product = Product::find($product_id);
        if (empty($product)) {
            return $this->sendError("product $product_id is not exists");
        }
        /** @var User $user */
        $user = auth('api')->user();
        $user->likeProduct($product_id,User::TYPE_UNLIKE);
        return $this->sendSuccess('You unlike successfully product');
    }

    public function userFavoriteProducts(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = auth('api')->user();
        return $this->sendResponse($user->products, 'User favorites products retrieve successfully');
    }

}
