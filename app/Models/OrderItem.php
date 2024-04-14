<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderItem extends Pivot
{
    use HasFactory;
    protected $table = 'order_items';
    public $incrementing = true;
    public $timestamps = false;
    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault(['name' => $this->product->name]);
    }
    public function orders()
    {
        return $this->belongsTo(Order::class);
    }


}