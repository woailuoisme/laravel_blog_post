<?php

namespace App\Models;

use App\Events\CartCheckoutEvent;
use App\Exceptions\ApiException;
use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Class Cart
 * @package App\Models
 * @version April 6, 2020, 2:15 am GMT
 *
 */
class Cart extends Model
{

    public $table = 'carts';


    protected array $dates = ['deleted_at'];


    public array $fillable = [
        'user_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer'
    ];

    /**
     * Validation rules
     * @var array
     */
    public static $rules = [

    ];

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_product')->withPivot('quantity')->withTimestamps();
    }

    public function existsProduct($product_id): bool
    {
        return $this->products()->wherePivot('product_id', $product_id)->exists();
    }

    public function addProductToCart($product_id): void
    {
        if ($this->existsProduct($product_id)) {
            $this->products()->wherePivot('product_id', $product_id)->increment('quantity');
        }
        $this->products()->attach($product_id, ['quantity' => 1]);
    }

    public function updateProductQuantity($product_id, $quantity): void
    {
        if ($this->existsProduct($product_id)) {
            $this->products()->updateExistingPivot('product_id', ['quantity' => $quantity]);
        }else{
            throw new ApiException("product $product_id is't exists in cart");
        }
    }

    public function clearProductsFromCart(): void
    {
        $this->products()->detach();
    }

    public function removeSingleProductFromCart($product_id): void
    {
        $this->products()->detach($product_id);
    }

    public function removeMultiProductsFromCart($product_ids): void
    {
        $this->products()->detach($product_ids);
    }

    public function cartCheckout(): void
    {
        if ($this->hasProducts()){
            throw  new ApiException('cart don\'t has any products');
        }
        /** @var User $user */
        $order = Order::create([
            'statusCode' => Order::ORDER_STATUS_PAY_PENDING,
            'user_id' => $this->user_id,
            'order_num' => Order::orderNumber(),
            'total_price' => $this->totalPrice(),
        ]);
        foreach ($this->products as $product) {
            $order->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
        }
        event(new CartCheckoutEvent($order));
        $this->products()->detach();
    }


    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function productCount()
    {
        return $this->products->count();
    }

    public function totalPrice()
    {
        $Totals = 0;
        foreach ($this->products as $product) {
            $total = $product->price * $product->pivot->quantity;
            $Totals += $total;
        }
        return $Totals;
    }

    public function hasProducts(): bool
    {
        return $this->products->count() > 0;
    }


}
