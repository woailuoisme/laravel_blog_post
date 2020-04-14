<?php


namespace App\Http\Service;


use App\Exceptions\ApiException;
use App\Models\Cart;
use App\Models\Product;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteProductService extends AppbaseService
{

    public function addFavoriteProductToCart($product_id): void
    {
         DB::transaction(function () use ($product_id) {
            $exists=Product::find($product_id)->exists();
            if (!$exists){
                $this->sendError("product is't exists");
            }
            /** @var User $user */
            $user = Auth::guard('api')->user();
            $user->addFavoriteToCart($product_id);
        });
    }

    public function checkoutToCart(): void
    {
        DB::transaction(function ()  {
            /** @var User $user */
            $user = Auth::guard('api')->user();
            /** @var Cart $cart */
            $user->checkoutFavoriteProduct();
        });
    }
}
