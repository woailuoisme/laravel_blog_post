<?php

namespace App\Models;

use App\User;
use Eloquent as Model;

/**
 * Class Order
 * @package App\Models
 * @version April 6, 2020, 2:15 am GMT
 */
class Order extends Model
{

    public $table = 'orders';

    protected $dates = ['deleted_at'];

    public static array $STATUS_CODE = [
        'created' => 'created',
        'pending' => 'pending',
        'processing' => 'processing',
        'payed' => 'payed',
        'completed' => 'canceled',
        'failed' => 'failed'
    ];

    public array $fillable = [
        'user_id', 'statusCode', 'order_num', 'total_price'
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
        return $this->belongsToMany(Product::class, 'order_product')->withPivot('quantity')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function orderNumber(){
        return hash('sha256', microtime());
    }

    public function productsCount(){
        return $this->products->count();
    }

    public function totalproductsPrice()
    {
        $Totals = 0;
        foreach ($this->products as $product) {
            $total = $product->price * $product->pivot->quantity;
            $Totals += $total;
        }
        return $Totals;
    }


}
