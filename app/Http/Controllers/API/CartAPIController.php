<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\Order;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Class CartController
 * @package App\Http\Controllers\API
 */
class CartAPIController extends AppBaseController
{
    /** @var  CartRepository */
    private $cartRepository;

    public function __construct(CartRepository $cartRepo)
    {
        $this->cartRepository = $cartRepo;
        $this->middleware('auth:api');
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $cart = Auth::guard('api')->user()->cart()->with('products')->get()->first();
        return $this->sendResponse($cart ? new CartResource($cart) : null, 'Carts retrieved successfully');
    }

    public function addToCart(Request $request)
    {
        $user = Auth::guard('api')->user();
        $cart = $user->cart;
//        dd(Auth::guard('api')->user());
        if (empty($cart)) {
            $cart = Cart::create([
                'user_id' => $user->id
            ]);
        }
        $product_id = $request->input('product_id');
        $product_exists = $cart->products()->wherePivot('product_id', $product_id)->exists();
        if ($product_exists) {
            $cart->products()->wherePivot('product_id', $product_id)->increment('quantity');
        } else {
            $cart->products()->attach($product_id, ['quantity' => 1]);
        }
        return $this->sendSuccess("product $product_id already had added in cart ");
    }

    public function updateProductQuantity(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $product_id = $request->input('product_id');
        $quantity = $request->input('quantity');
        $cart = Auth::guard('api')->user()->cart;
        $product_exists = $cart->products()->wherePivot('product_id', $product_id)->exists();
        if ($product_exists) {
            $cart->products()->updateExistingPivot($product_id, ['quantity' => $quantity]);
            return $this->sendSuccess("product $product_id  quantity update $quantity ");
        } else {
            return $this->sendError("product $product_id is not exist cart", 400);
        }
    }

    public function clearCart(): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('api')->user();
        $user->cart->products()->detach();
        return $this->sendSuccess("User({$user->name}{$user->id})cart already has cleared ");
    }

    public function checkout(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = Auth::guard('api')->user();
        $cart = $user->cart;
        if (!$cart->cartHasProduct()) {
            return $this->sendError('cart don\'t has any products');
        }
        $order = Order::create([
            'statusCode' => Order::$STATUS_CODE['created'],
            'user_id' => $user->id,
            'order_num' => Order::orderNumber(),
            'total_price' => $cart->totalPrice(),
        ]);
        foreach ($cart->products as $product) {
            $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
        }
        $cart->products()->detach();
        return $this->sendSuccess("Order {$order->id} has been created");
    }

}
