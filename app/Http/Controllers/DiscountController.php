<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDiscountRequest;
use App\Models\Discount;
use Illuminate\Http\Request;
use Psy\Util\Str;

class DiscountController extends Controller
{
    public function create(CreateDiscountRequest $request)
    {
        $random = bin2hex(random_bytes(3));
        $discount = Discount::create($request->merge([
            'code'=>$random
        ])->toArray());
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
