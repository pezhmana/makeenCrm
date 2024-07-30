<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrdersRequest;
use App\Http\Requests\EditOrdersRequest;
use App\Models\Discount;
use App\Models\order;
use App\Models\Product;
use Faker\Core\Number;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index($id = null){
        if($id) {
            $orders = order::where('id', $id)->first();

            $orders = $orders->OrderBy('id', 'DESC')->paginate(10);


            return response()->json($orders);
        }
    }
    public function create(CreateOrdersRequest $request){
        $user = Auth::user();
        $product = Product::where('id',$request->product_id)->first();
        $exist = Order::where('user_id',$user->id)->where('product_id',$request->product_id);
        if($exist->exists()){
            $order = 'این سفارش قبلا ثبت شده';
        }else {
            if ($request->discount) {
                $discount = Discount::where('code', $request->discount)->first();
                if (!$discount) {
                    return Response()->json('کد تخفیف نامعتبر است');
                } else {
                    $percent = $discount->percent;
                    $percent = $percent / 100;
                    if ($product->discount_price != null) {
                        $order = order::create($request->merge([
                            'user_id' => $user->id,
                            'sum' => $product->discount_price - ($product->discount_price * $percent)
                        ])->toArray());
                    } else {
                        $order = order::create($request->merge([
                            'user_id' => $user->id,
                            'sum' => $product->price - ($product->price * $percent)
                        ])->toArray());
                    }
                  Discount::where('code', $request->discount)->decrement('amount');
                }
            } else {
                if ($product->discount_price != null) {
                    $order = order::create($request->merge([
                        'user_id' => $user->id,
                        'sum' => $product->discount_price
                    ])->toArray());
                } else {
                    $order = order::create($request->merge([
                        'user_id' => $user->id,
                        'sum' => $product->price
                    ])->toArray());
                }
                $user->assignRole('student');
            }
        }
        return response()->json($order);
    }
    public function edit(EditOrdersRequest $request,$id)
    {
        $order = order::where('id',$id)->update($request->toArray());
        return response()->json($order);

    }
    public function delete($id)
    {
        $order = order::destroy($id);
        return response()->json($order);

    }

}
