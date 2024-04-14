<?php

namespace App\Models;

use App\Models\Scopes\StoreScope;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name','slug','description','image','category_id','store_id','price','compare_price','status'];
    protected static function booted()
    {
         static::addGlobalScope(new StoreScope);
    }
    public function category()
    {
        return $this->belongsTo(Product::class,'category_id','id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class,'store_id','id');
    }
    public function tags()
    {
        return $this->belongsToMany(
            Tag::class);
    }
    protected function scopeActive(Builder $builder)
    {
$builder->where('status','=','active');
    }
    //accessor
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return 'https://www.incathlab.com/images/products/default_product.png';
        }
        if (Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }
        return asset('storage/' . $this->image);
    }
    public function getSalePercentAttribute()
    {
        if(!$this->compare_price){
            return 0;
        }
        return number_format(100 - (100 * $this->price/$this->compare_price),1);
    }
}
