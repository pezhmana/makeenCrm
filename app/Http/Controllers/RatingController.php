<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRatingRequest;
use App\Models\Comment;
use App\Models\Order;
use App\Models\Product;
use App\Models\rating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    public function addrating(CreateRatingRequest $request, $id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->where('product_id', $id);
        if ($order->exists()) {
            $existingRating = Comment::where('user_id', $user->id)->where('product_id', $id);
            $exist = $existingRating->first();
//            dd($exist);
            if($existingRating->exists()){
                    $rating = $existingRating->update([
                        'rating' => $request->rating,
                    ]);
            } else {
                $rating = Comment::create([
                    'product_id' => $id,
                    'user_id' => $user->id,
                    'rating' => $request->rating,
                    'comment_id'=>0
                ]);
            }
        } else {
            $rating = 'برای نمره دادن باید دوره را خریداری نمایید';
        }

        return response()->json($rating);
    }
}
