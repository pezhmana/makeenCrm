<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRatingRequest;
use App\Models\Product;
use App\Models\rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function addrating(CreateRatingRequest $request , $id){
        $user = Auth::user();
        $exist = rating::where('user_id',$id)->where('product_id',$id);
        if($exist->exists()){
           $rating = $exist->update([
                'product_id' => $id,
                'user_id'=>$user->id,
                'rating'=>$request->rating
            ]);
        }else{
        $rating = Rating::create([
            'product_id' => $id,
            'user_id'=>$user->id,
            'rating'=>$request->rating
        ]);}
        return response()->json($rating);
    }

//    public function index($id){
//        $products = Product::find($id);
//       $products = $products->withAvg('ratings','rating')->get();
//        return response()->json($products);
//    }


}
