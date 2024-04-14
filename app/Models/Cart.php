<?php

namespace App\Models;

use App\Observers\CartObserver;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class Cart extends Model
{
    use HasFactory;
    public $incrementing = false; //primary key doesn't increment
    protected $fillable = ['cookie_id','user_id','product_id','quantity','options'];

    //Events (Observers)
    //creating before creating in database, created after creating in database
    //updating, updating .. saving, saved
    //deleting, deleted  //restoring, restored //retrieved
    protected static function booted()
    {
        static::observe(CartObserver::class);
        static::addGlobalScope('cookie_id', function (Builder $builder) {
            $builder->where('cookie_id', '=', Cart::getCookieId());
        });
        //  static::creating(function (Cart $cart.js){
        //     $cart.js->id = Str::uuid();
        //      });

    }
        public static function getCookieId()
        {
            $cookie_id= Cookie::get('cookie_id');
            if(!$cookie_id){
                $cookie_id = Str::uuid();
                Cookie::queue('cookie_id',$cookie_id,30 * 24 * 60);
            }
            return $cookie_id;
        }

        public function user()
        {
            return $this->belongsTo(User::class)->withDefault(['name'=>'anyonmous']);
        }
        public function product()
        {
            return $this->belongsTo(Product::class);
        }


}
