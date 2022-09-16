<?php

namespace App\Models;

use Log;
use Osiset\ShopifyApp\Traits\ShopModel;
use Illuminate\Notifications\Notifiable;
use Osiset\ShopifyApp\Storage\Models\Charge;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Osiset\ShopifyApp\Contracts\ShopModel as IShopModel;
use LaravelFillableRelations\Eloquent\Concerns\HasFillableRelations;

class User extends Authenticatable implements IShopModel
{
    use HasFactory, Notifiable ,ShopModel,HasFillableRelations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plan_id','theme_id','feed_page','shopify_grandfathered','shopify_namespace','shopify_freemium','plan_id','deleted_at','password_updated_at','admin_email','admin_phone','country_name'
    ];

    protected $fillable_relations = ['pixels','chargess'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public static function boot()
    {
       parent::boot();

       static::deleted(function($user){
            Log::info("Model Soft Delete:".get_class($user)."-ID-".$user->id);
            $user = User::withTrashed()->find($user->id);
            $user->install = 0;
            $user->save();
       });       
    }
    
    public function pixels()
    {
        return $this->hasMany(Pixel::class,'shop_id');
    }

    public function chargess(){
        return $this->hasMany(Charge::class,'user_id','id');
    }
}
