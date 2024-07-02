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
            $orders = order::where('id', $id)->first();

            $orders = $orders->OrderBy('id', 'DESC')->paginate(10);


            return response()->json($orders);
        }
    }
    public function create(Request $request){
        $order = order::create($request->toArray());
//        $order->products()->attach($request->products_id);
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
//    public function detach (Request $request, $id){
//        $order = order::find($id);
//        $order->products()->detach($request->products_id);
//        return response()->json($order);
//    }
}
