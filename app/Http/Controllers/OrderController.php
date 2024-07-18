<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrdersRequest;
use App\Http\Requests\EditOrdersRequest;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index($id = null){
        if($id) {
            $orders = order::where('id', $id)->first();

            $orders = $orders->OrderBy('id', 'DESC')->paginate(10);


            return response()->json($orders);
        }
    }
    public function create(Request $request){
        $user = Auth::user();
        $exist = Order::where('user_id',$user->id)->where('product_id',$request->product_id);
//        dd($exist);
        if($exist->exists()){
            $order = 'این سفارش قبلا ثبت شده';
        }else{
            $order = order::create($request->merge([
                'user_id' => $user->id
            ])->toArray());
            $user->assignRole('student');
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
