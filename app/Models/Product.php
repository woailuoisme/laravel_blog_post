<?php

namespace App\Models;

use App\User;
use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Product
 * @package App\Models
 * @version April 6, 2020, 1:32 am GMT
 *
 */
class Product extends Model
{

    public  $table = 'products';


    protected array $dates = ['deleted_at'];


    public array $fillable = [
        'name','description','content','imageURL','sku','discount','price'
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

    public function favorites(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Order::class,'order_product')->withPivot('quantity')->withTimestamps();
    }

    public function carts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Cart::class,'cart_product')->withPivot('quantity')->withTimestamps();
    }

    public function likeUsers(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_like')->withPivot('like')->withTimestamps();
    }

    public function upLikes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->likeUsers()->wherePivot('vote', 1);
    }




    public function downLikes(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->likeUsers()->wherePivot('vote', -1);
    }
    public function upLikesUserCount(): int
    {
        return $this->likeUsers()->count();
    }

    public function downLikesUserCount(): int
    {
        return $this->likeUsers()->wherePivot('vote', -1)->count();
    }
    public function likeCount(){
        return $this->upLikes()->sum('like')+$this->downLikes()->sum('like');
    }

    public function checkUserLike($user_id): bool
    {
        return $this->likeUsers()->wherePivot('user_id', $user_id)->exists();
    }

}
