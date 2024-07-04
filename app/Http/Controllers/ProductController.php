<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(Request $request)
    {

        $product = product::create($request->toArray());
        return response()->json($product);}


    public function index($id = null) {
        $product = new product();
        if($id){
            $product =$product->where('id', $id)->first();
            return response()->json($product);
        }
        if(request("most")){
            $product= Order::select('product_id')
                ->groupBy('product_id')
                ->orderByRaw('COUNT(*) DESC')
                ->limit(10)
                ->paginate(10);
            return response()->json($product);
        }
          $product =$product->orderby('id', 'desc')->paginate(10);
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
}
