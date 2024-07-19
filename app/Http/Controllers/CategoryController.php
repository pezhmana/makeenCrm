<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoriesRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(CreateCategoriesRequest $request){
        $categories = Category::create($request->toArray());
        if($request->icon){
            $categories->addMediaFromRequest('icon')->toMediaCollection('icon');
        }
        if($request->category_id){
            $categories->category()->attach($request->category_id);
        }
        return response()->json($categories);
    }

    public function index($id = null){
        $category = new Category();
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

    public function add($id , CreateCategoriesRequest $request){
        $type = $request->type;
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

    public function edit($id , CreateCategoriesRequest $request){
        $category = Category::find( $id);
        $category->update($request->toArray());
        return response()->json($category);
    }
}
