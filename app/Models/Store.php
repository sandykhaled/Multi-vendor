<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Store extends Model
{
//    const  CREATED_AT = 'created_on';
//    const  UPDATED_AT = 'updated_on';

    use HasFactory,Notifiable;
    public function products()
    {
        return $this->hasMany(Product::class,'store_id','id');
    }

}
