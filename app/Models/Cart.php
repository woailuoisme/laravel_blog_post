<?php

namespace App\Models;

use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Cart
 * @package App\Models
 * @version April 6, 2020, 2:15 am GMT
 *
 */
class Cart extends Model
{

    public $table = 'carts';


    protected $dates = ['deleted_at'];


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
     *
     * @var array
     */
    public static $rules = [

    ];

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'cart_product')->withPivot('quantity')->withTimestamps();
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

    public function cartHasProduct(): bool
    {
        return $this->products->count() > 0;
    }


}
