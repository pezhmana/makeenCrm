<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function create(Request $request)
    {

        $discount = Discount::create($request->toArray());
        return response()->json($discount);
    }


    public function index($id = null)
    {
        if ($id) {
            $discount = Discount::where('id', $id)->first();
        } else {
            $discount = Discount::orderby('id', 'desc')->paginate(10);
        }
        return response()->json($discount);
    }

    public function edit(Request $request, $id)
    {
        $discount = Discount::where('id', $id)->update($request->toArray());
        return response()->json($discount);
    }

    public function delete($id)
    {
        $discount = Discount::where('id', $id)->delete();
        return response()->json($discount);
    }
}
