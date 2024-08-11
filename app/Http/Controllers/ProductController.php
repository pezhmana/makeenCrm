<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\CreateProductsRequest;
use App\Http\Requests\EditProductsRequest;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\rating;
use App\Models\Teacher;
use http\Url;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Video;
use Spatie\Permission\Models\Role;

class ProductController extends Controller
{
    public function create(Request $request)
    {

        $product = product::create($request->toArray());

        if($request->image){
            $product->addMediaFromRequest('image')->toMediaCollection("product.image");
        }

        if($request->suggest){
            $id= Category::where('name','پیشنهادی')->first()->id;
            $product->categories()->attach($id);
        }

        return response()->json($product);}


    public function index($id = null) {
        $product = new product();
        $product = $product->withAvg('comments','rating')->with('teacher')->withCount('orders');
            if($id){
                $product = Product::find($id);
                $chapter = Product::find($id)->chapters()->first();
                return response()->json([$product,$chapter]);
        }
        if(request("most")){
            $topProductIds = Order::select('product_id', DB::raw('COUNT(*) as total'))
                ->groupBy('product_id')
                ->orderBy('total', 'DESC')
                ->limit(10)
                ->pluck('product_id');
            $product= Product::whereIn('id', $topProductIds)
                ->withAvg('comments','rating')->with('categories')->with('teacher')->withCount('orders')->get();
            return response()->json($product);
        }
        if(Request('search')){
            $product = Product::where('name','like','%'.Request('search').'%');
        }


        if(Request('count')) {
            $product = Product::count();
            return response()->json($product);
        }

        if(Request('member')){
            $product =Role::findByName('user' , 'web')->count();
            return response()->json($product);
        }
        if(Request('discount')){
            $product = $product->where('discount_price','!=',null);
        }
        if(Request('expensive')){
            $product = $product->orderByDesc('price');
        }
        if(Request('cheap')){
            $product = $product->orderBy('price','asc');
        }
        if(Request('free')){
            $product =$product->where('price',0)->orWhere('discount_price',0);
        }
        if(Request('notfree')){
            $product =$product->where('price','!=',0)->orWhere('discount_price','!=',0);
        }
        if(Request('category')){
            $id =Request('category');
            $product = Product::whereHas('categories',function (Builder $query)use($id){
               $query->where('name', $id);
            });
        }
        if(Request('orderRating')){
            $product = $product->orderBy('ratings_avg_rating','desc');
        }
        if(Request('teacher')){
            $product = $product->where('teacher_id',Request('teacher'));
        }
        if(Request('rating')){
            $product = Comment::sum('rating');
            $count = Comment::count('rating');
            $product = $product*20;
            $product = $product/$count.'%';
            return response()->json($product);

        }

          $product =$product->paginate(10);
        return response()->json($product);

        }




    public function edit(EditProductsRequest $request, $id){
        $product = product::where('id', $id)->update($request->toArray());
        return response()->json('تغییرات با موفقیت انجام شد');
    }
    public function delete($id){
        $product = product::where('id',  $id)->delete();
        return response()->json($product);
    }

    public function addmedia(Request $request, $id)
    {
        $exist = Chapter::where('title', $request->chapter)->where('product_id', $id)->first();

        if ($exist) {
            $video = $exist->videos()->create([
                'title' => $request->title,
            ]);
        } else {
            $season = Product::find($id)->chapters()->create([
                'title' => $request->chapter,
            ]);

            $video = $season->videos()->create([
                'title' => $request->title,
            ]);
        }

        $media = $video->addMediaFromRequest('media')->toMediaCollection('product.videos');
        $url = $media->getUrl();
        $video->update([
            'url' => $url,
        ]);

        return response()->json($video);
    }

    public function adminindex(){
        $product = Product::select(['id','name','price','teacher_id'])
            ->with(['categories'=>function (Builder $query) {
                $query->select('categories.id', 'categories.name');
            }])
            ->with(['teacher'=>function (Builder $query) {
            $query->select('id','name');
        }])->get();
        $product->each(function ($product) {
            $product->product_image = $product->getProductImageAttribute();
        });

        return response()->json($product);
    }





}
