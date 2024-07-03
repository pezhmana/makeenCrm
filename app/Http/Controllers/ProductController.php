<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(Request $request)
    {

        $product = product::create($request->toArray());
        return response()->json($product);}


    public function index($id = null) {
        if($id){
            $product = product::where('id', $id)->first();
        }
        else{
            $product = product::orderby('id', 'desc')->paginate(3);
        }
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
