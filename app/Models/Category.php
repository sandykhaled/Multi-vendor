<?php

namespace App\Models;

use App\Rules\filter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Category extends Model
{
    use HasFactory , SoftDeletes ;
    //white list
    protected $fillable=['name','parent_id','description','image','status','slug'];
    //black list
    protected $guarded=['name'];
    public static function rules($id=0){
        return [
            'name'=>['required'
                ,'string',
                'min:3',
                'max:255',
                Rule::unique('categories','name')->ignore($id),
                'filters:php,laravel,css'],
            'parent_id'=>
                [ 'nullable' , 'int' , 'exists:categories,id' ] ,
            'image'=>
                [ 'image' , 'max:1048576' , 'dimension:min_width=100,min_height=100' ],
            'status'=>'required|in:active,archived'

        ];
    }
    public function products()
    {
        return $this->hasMany(Product::class,'category_id','id');
    }
    public function parent()
    {
        return $this->belongsTo(Category::class,'parent_id','id')
            ->withDefault(['name'=>'Main Category']);
    }
    public function childs()
    {
        return $this->hasMany(Category::class,'parent_id','id');
    }
    public function ScopeActive(Builder $builder)
    {
        $builder->where('status','active');
    }
    public function ScopeStatus(Builder $builder,$status)
    {
        $builder->where('status','=',$status);
    }
    public function ScopeFilter(Builder $builder,$filter)
    {
        $builder->when($filter['name'] ?? false,function ($builder,$value){
            $builder->where('name','LIKE',"%{$value}%");

        });
       $builder->when($filter['status'] ?? false,function ($builder,$value){
          $builder->where('status',$value);
       });
    }
}
