<?php

namespace App\Http\Controllers\API;

use App\Events\CartCheckoutEvent;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\CartResource;
use App\Http\Service\CartService;
use App\Models\Cart;
use App\Models\Order;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CartController
 * @package App\Http\Controllers\API
 */
class CartController extends AppBaseController
{
    /**
     * @var CartService
     */
    private $cartService;

    public function __construct(CartRepository $cartRepo, CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->middleware('auth:api');
    }

    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $cart = Auth::guard('api')->user()->cart()->with('products')->get()->first();
        return $this->sendResponse($cart ? new CartResource($cart) : null, 'Carts retrieved successfully');
    }

    public function addProductToCart(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
        ]);
        return $this->cartService->addProductToCart($validatedData['product_id']);
    }

    public function updateProductQuantity(Request $request): ?\Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        return $this->cartService->updateProductQuantity(
            $validatedData['product_id'],
            $validatedData['quantity']);
    }

    public function clearCart(): \Illuminate\Http\JsonResponse
    {
        return $this->cartService->clearCart();
    }

    public function removeSingle(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
        ]);
        return $this->cartService->removeSingle($validatedData['product_id']);
    }

    public function removeSome(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'product_ids' => 'required|array',
        ]);

        return $this->cartService->removeMulti($validatedData['product_ids']);
    }

    public function checkout(Request $request): \Illuminate\Http\JsonResponse
    {
        return $this->cartService->checkout();
    }

}
