<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoriesRequest;
use App\Http\Requests\EditCategoriesRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function create(CreateCategoriesRequest $request){
        $categories = Category::create($request->toArray());
        if($request->icon){
            $categories->addMediaFromRequest('icon')->toMediaCollection('category.icon');
        }
        if($request->category_id){
            $categories->category()->attach($request->category_id);
        }
        return response()->json($categories);
    }

    public function index($id = null){
        $category = new Category();
        $category = $category->with('category');
        if($id){
            $Category =$category->where('id',$id)->first();
            return response()->json($Category);
        }
        if(Request('search')){
            $search = Request('search');
            $category = Category::whereHas('category', function (Builder $query) use($search){
               $query->where('category_id',$search);
            });
        }
        $category = $category->orderByDesc('id')->paginate(10);
        return Response()->json($category);

    }

    public function add($id , EditCategoriesRequest $request){
        $type = $request->type;
        $exist = DB::table('categoryables')
            ->where('category_id',$request->category_id)->where('categoryable_id',$id);
        if($exist->exists()){
            return response()->json('وجود دارد');
        }
        if ($type == 'post'){
            $category = Post::find($id);
            $category->categories()->attach($request->category_id);
        }
        if ($type == 'product'){
            $category = Product::find($id);
            $category->categories()->attach($request->category_id);
        }
        if ($type == 'category'){
            $category = Category::find($id);
            $category->category()->attach($request->category_id);
        }
        return response()->json($category);
    }

    public function delete($id)
    {
        $category = Category::destroy($id);
        return response()->json('با موفقیا حذف شد');
    }

    public function edit($id , EditCategoriesRequest $request){
        $category = Category::find( $id);
        $category->update($request->toArray());
        return response()->json('تغییرات با موفقیت انجام شد');
    }
}
