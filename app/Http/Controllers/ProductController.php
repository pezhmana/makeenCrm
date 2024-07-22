<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductsRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\rating;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function create(Request $request)
    {

        $product = product::create($request->toArray());
        if($request->image){
            $product->addMediaFromRequest('image')->toMediaCollection("product.image");
        }

        return response()->json($product);}


    public function index($id = null)
    {
        $product = new product();

        $product = $product->withAvg('ratings','rating');
        if ($id) {
            $product = $product->where('id', $id)->first();
            $product = $product->withAvg('ratings', 'rating')->get();

            $product = $product->withAvg('ratings', 'rating')->with('categories');
            if ($id) {
                $product = $product->where('id', $id)->first();
                $product = $product->withAvg('ratings', 'rating')->with('categories')->get();

                return response()->json($product);
            }
            if (request("most")) {
                $topProductIds = Order::select('product_id', DB::raw('COUNT(*) as total'))
                    ->groupBy('product_id')
                    ->orderBy('total', 'DESC')
                    ->limit(10)
                    ->pluck('product_id');
                $product = Product::whereIn('id', $topProductIds)->get();
                return response()->json($product);
            }
            if (Request('search')) {
                $product = Product::where('name', 'like', '%' . Request('search') . '%');
            }
            if (Request('count')) {
                $product = Product::count();
                return response()->json($product);
            }
            if (Request('member')) {
                $order = new order();
                $product = $product->withCount('orders');
            }
            if (Request('discount')) {
                $product = $product->where('discount_price', '!=', null);
            }
            if (Request('expensive')) {
                $product = $product->orderByDesc('price');
            }
            if (Request('cheap')) {
                $product = $product->orderBy('price', 'asc');
            }
            if (Request('free')) {
                $product = $product->where('price', 0)->orWhere('discount_price', 0);
            }
            if (Request('notfree')) {
                $product = $product->where('price', '!=', 0)->orWhere('discount_price', '!=', 0);
            }
            if (Request('category')) {
                $id = Request('category');
                $product = Product::whereHas('categories', function (Builder $query) use ($id) {
                    $query->where('category_id', $id);
                });
            }
            if (Request('rating')) {
                $product = rating::sum('rating');
                $count = rating::count();
                $product = $product * 20;
                $product = $product / $count . '%';
                return response()->json($product);

            }

            $product = $product->paginate(10);
            return response()->json($product);
        }

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
        $video =$product->addMediaFromRequest('media')->toMediaCollection("$product->name.season$request->season");
        return response()->json($video);
    }


}
