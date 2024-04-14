<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $products = Product::with(['category','store'])->paginate();
            return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
//        $this->authorize('update', $product);
        $tags = implode(',', $product->tags()->pluck('name')->toArray());
        return view('dashboard.products.edit',compact(['product','tags']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $product->update($request->except('tags'));
        $tags = json_decode($request->post('tags'));
        $tagsId=[];
        $savedTags = Tag::all();
        foreach ($tags as $item){
            $slug = Str::slug($item->value);
            $tag = $savedTags->where('slug',$slug)->first();
            if(!$tag){
                $tag=Tag::create([
                    'name'=>$item->value,
                    'slug'=>$slug
                ]);
            }
            $tagsId[] = $tag->id;
        }
        $product->tags()->sync($tagsId);
        return redirect()->route('dashboard.products.index')->with('success','Product Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
