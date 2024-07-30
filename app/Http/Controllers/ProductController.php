<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductsRequest;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\rating;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function create(CreateProductsRequest $request)

    {

        $product = product::create($request->toArray());
        if($request->image){
            $product->addMediaFromRequest('image')->toMediaCollection("product.image");
        }
        if($request->season){
            foreach ($request->season as $season){
                DB::table('collections')->insert([
                    'collection'=>$product->name.$season
                ]);
            }
        }


        if($request->suggest){
            $id= Category::where('name','suggest')->first()->id;
            $product->categories()->attach($id);
        }

        return response()->json($product);}


        public function index($id = null) {
        $product = new product();
        $product = $product->withAvg('comments','rating')->with('categories');
        if($id){
            $product = Product::find($id);
            $user =Auth::user()->id;
            $purchase = Order::where('user_id',$user)->where('product_id',$id);
            if($purchase->exists()){
                $media = Product::find($id)->getMedia('*');
                $video = [];
                foreach ($media as $mediaItem) {
                    $video[] = $mediaItem->getUrl();
                }
            }else{
                $video = 'برای دسترسی باید دوره را خریداری نمایید';
            }
            return response()->json([$product,$video]);
        }
        if(request("most")){
            $topProductIds = Order::select('product_id', DB::raw('COUNT(*) as total'))
                ->groupBy('product_id')
                ->orderBy('total', 'DESC')
                ->limit(10)
                ->pluck('product_id');
            $product= Product::whereIn('id', $topProductIds)->get();
            return response()->json($product);
        }
        if(Request('search')){
            $product = Product::where('name','like','%'.Request('search').'%');
        }

        if(Request('count')){
            $product = Product::count();
            return response()->json($product);
        }


        if(Request('member')){
            $order =new order();
            $product = $product->withCount('orders');
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

                $query->where('category_id', $id);

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






    public function edit(Request $request, $id){
        $product = product::where('id', $id)->update($request->toArray());
        return response()->json($product);
    }
        public function delete($id){
        $product = product::where('id',  $id)->delete();
        return response()->json($product);
    }

        public function addmedia($id , Request $request){
        $product = Product::find($id);
        $collection = DB::table('collections')->find($request->season);
        $video =$product->addMediaFromRequest('media')->toMediaCollection($collection->collection);
        return response()->json($video);
    }


    }
