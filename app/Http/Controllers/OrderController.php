<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrdersRequest;
use App\Http\Requests\EditOrdersRequest;
use App\Models\order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index($id = null){
        if($id) {
            $orders = Order::where('id', $id)->first();

            $orders = $orders->OrderBy('id', 'DESC')->paginate(10);


            return response()->json($orders);
        }
    }
    public function create(CreateOrdersRequest $request){
        $order = Order::create($request->toArray());
        $order->products()->attach($request->products_id);
        return response()->json($order);
    }
    public function edit(EditOrdersRequest $request,$id)
    {
        $order = Order::where('id',$id)->update($request->toArray());
        return response()->json($order);

    }
    public function delete($id)
    {
        $order = Order::destroy($id);
        return response()->json($order);

    }
//    public function detach (Request $request, $id){
//        $order = order::find($id);
//        $order->products()->detach($request->products_id);
//        return response()->json($order);
//    }
}
