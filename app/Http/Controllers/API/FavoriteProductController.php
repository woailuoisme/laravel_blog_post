<?php


namespace App\Http\Controllers\API;


use App\Events\CartCheckoutEvent;
use App\Exceptions\ApiException;
use App\Http\Controllers\AppBaseController;
use App\Http\Service\FavoriteProductService;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\User;
use Illuminate\Support\Facades\Auth;

class FavoriteProductController extends AppBaseController
{
    /** @var FavoriteProductService  */
    public  $service;
    public function __construct(FavoriteProductService $service)
    {
        $this->middleware('auth:api');
        $this->service =$service;
    }

    public function addFavoriteProductToCart($product_id): \Illuminate\Http\JsonResponse
    {
        $this->service->addFavoriteProductToCart((int)$product_id);
        return $this->sendSuccess('Favorite product add successfully to cart ');
    }
    public function addAllToCart(): \Illuminate\Http\JsonResponse
    {
        $this->service->checkoutToCart();
        return $this->sendSuccess('Favorite product checkout successfully to cart');
    }

}
