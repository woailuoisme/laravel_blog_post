<?php

namespace App;

use App\Exceptions\ApiException;
use App\Models\Cart;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\Profile;
use App\Traits\UserFavoriteProductTrait;
use App\Traits\UserLikeProductsTrait;
use App\Traits\UserRules;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\HasApiTokens;
use mysql_xdevapi\Exception;
use phpDocumentor\Reflection\Types\Boolean;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, Notifiable, UserRules;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
//    protected array $fillable = [
//        'name', 'email', 'password','reset_token','reset_',
//    ];
    protected array $guarded = ['password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected array $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public const TYPE_LIKE = 1;
    public const TYPE_UNLIKE = -1;

    public function profile(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Profile::class);
    }

    public function posts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function cart(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function favoriteProducts(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'user_favorite_product')->withTimestamps();
    }
    public function existsFavoriteProduct($product_id): bool
    {
        return $this->favoriteProducts()->wherePivot('product_id', $product_id)->exists();
    }

    public function hasFavoriteProducts(): bool
    {
        return $this->favoriteProductsCount() > 0;
    }

    public function favoriteProduct($product_id, $cancel = false): void
    {
        $cancel ? $this->favoriteProducts()->detach($product_id) : $this->favoriteProducts()->attach($product_id);
    }

    public function favoriteProductsCount():int
    {
        return $this->favoriteProducts->count();
    }

    public function addFavoriteToCart($product_id): void
    {
        if ($this->existsFavoriteProduct($product_id)) {
            /** @var Cart $cart */
            $cart = $this->cart;
            $cart->addProductToCart($product_id);
            $this->favoriteProducts()->detach($product_id);
        } else {
            throw new ApiException("product is't been favorite");
        }
    }

    public function checkoutFavoriteProduct(): void
    {
        /** @var Cart $cart */
        $cart = $this->cart;
        if (empty($cart)){
            $cart = Cart::create([
                'user_id' => $this->id,
            ]);
        }
        if ($this->hasFavoriteProducts()) {
            foreach ($this->favoriteProducts as $product) {
                $cart->products()->attach($product->id, ['quantity' => 1]);
            }
            $this->favoriteProducts()->detach();
        }else{
            throw new ApiException('User is\'t favorite any product');
        }
    }

    public function likeProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'user_like_product')
            ->withPivot('like')->withTimestamps();
    }

    public function likeProductsCount()
    {
        return $this->likeProducts->count();
    }

    public function existsLikeProduct($product_id): bool
    {
        return $this->likeProducts()->wherePivot('product_id', $product_id)->exists();
    }

    public function likeProduct($product_id, $like): void
    {
        if ($this->existsLikeProduct($product_id)) {
            $this->likeProducts()->updateExistingPivot($product_id, ['like' => $like]);
        }
        $this->likeProducts()->attach($product_id, ['like' => $like]);
    }

    public function hasLikeProducts(): bool
    {
        return $this->likeProductsCount() > 0;
    }

    public function avatarUrl()
    {
        return Storage::url($this->profile->avatar);
    }

    /**
     * Automatically creates hash for the user password.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function setResetTokenAttribute($value): void
    {
//        $this->attributes['reset_token'] = Uuid::uuid4()->toString();
    }

    public function checkPassword($value): Boolean
    {
        return Hash::check($value, $this->attributes['password']);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }
}
