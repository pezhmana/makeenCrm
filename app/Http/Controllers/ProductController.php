<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Http\Requests\CreateProductsRequest;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\rating;
use http\Url;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Excel;
use Spatie\MediaLibrary\Conversions\ImageGenerators\Video;

class ProductController extends Controller
{
    public function create(Request $request)
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
                $chapter = Product::find($id)->chapters()->first();
                if(Request('video')){
                    $id=Request('video');
                    $url =$chapter->videos->find($id);
                    if($url == null){
                        return response()->json('همچین ویدیو ای برای دوره وجود ندارد');
                    }
                    $user_video = DB::table('user_video')->where('user_id',$user)->where('video_id',$url->id);
                    if(!$user_video->exists()){
                        Auth::user()->videos()->attach($url->id,['product_id'=>$product->id]);
                    }
                    $url = $url->url;
                    return response()->json($url);
                }
            }else{
                $video = 'برای دسترسی باید دوره را خریداری نمایید';
            }
            return response()->json([$product,$chapter]);
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


        if(Request('count')) {
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





}
