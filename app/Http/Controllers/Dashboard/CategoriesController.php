<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // SELECT a.* , b.name as parent_name
        // FROM categories AS a
        // LEFT JOIN categories AS b WHERE a.parent_id == b.id
        $request = \request();
        $categories=Category::/*leftJoin('categories as parents','parents.id','=','categories.parent_id')
            ->select(['categories.*','parents.name as parent_name'])*/
              with('parent')
           /* ->select('categories.*')
            ->selectRaw('(SELECT COUNT(*) FROM products WHERE category_id = categories.id) as products_count')*/
            ->withCount(['products'=>function ($query){
                $query->where('status','=','active');

           }])
            ->filter($request->query())
            ->withoutTrashed()
            ->orderBy('categories.name')
            ->paginate(1);
        return view('dashboard.categories.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parents=Category::all();
        $category=new Category();
        return view('dashboard.categories.create',compact('parents','category'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request)
    {
//        $clean_data=$request->validate(Category::rules(),);
        $request->merge([
            'slug'=>Str::slug($request->post('name'))
        ]);
        $data=$request->except('image');
        $data['image']=$this->uploadFile($request);
        //Mass Assigment
        $category=Category::create($data);
        return redirect()->route('dashboard.categories.index')
            ->with('success','Category created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return view('dashboard.categories.show',compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try{
            $category=Category::findOrFail($id);
        }
        catch(\Exception $e){
            return redirect()->route('dashboard.categories.index')
                ->with('info','Record Not Found!');
        }
        $parents=Category::where('id','<>',$id)
           ->where(function($query) use ($id){
                   $query->whereNull('parent_id')
                   ->orWhere('parent_id','<>',$id);
           })
            ->get();
        return view('dashboard.categories.edit',compact('category','parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(Category::rules($id));
        $category=Category::find($id);
        $old_image=$category->image;
        $data=$request->except('image');
        $new_image=$this->uploadFile($request);
        if($new_image){
            $data['image']=$new_image;
        }
        $category->update($data);
//        if($old_image && $new_image){
//            Storage::disk('public')->delete($old_image);
//        }
        return redirect()->route('dashboard.categories.index')
            ->with('success','Category updated!');



    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category=Category::findOrFail($id);
        $category->delete();
        if($category->image){
            Storage::disk('public')->delete($category->image);
        }

        return redirect()->route('dashboard.categories.index')
            ->with('success','Category Deleted!');

    }
    protected function uploadFile(Request $request){
        if(! $request->hasFile('image')){
            return;
        }

            $file = $request->file('image');
            $path=$file->store('uploads',['disk'=>'public']);
            return $path;

    }
    public function trash()
    {
        $categories = Category::withTrashed()->paginate();
        return view('dashboard.categories.trash',compact('categories'));
    }
    public function restore(Request $request,$id)
    {
        $category= Category::onlyTrashed()->findOrFail($id);
        $category->restore();
        return redirect()->route('dashboard.categories.trash')
            ->with('Success','Category restored!');
    }
    public function forceDelete($id)
    {
        $category= Category::onlyTrashed()->findOrFail($id);
        $category->forceDelete();
        return redirect()->route('dashboard.categories.trash')
            ->with('Success','Category deleted forever!');
    }
}
