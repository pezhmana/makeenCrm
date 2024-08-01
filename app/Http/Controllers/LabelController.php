<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLabelRequest;
use App\Http\Requests\EditlabelRequest;
use App\Models\Label;
use App\Models\Message;
use App\Models\Product;
use App\Models\User;
use http\Env\Response;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LabelController extends Controller
{
    public function create(CreateLabelRequest $request)
    {
        $label =Label::create($request->toArray());
        return response()->json($label);
    }

    public function index($id = null)
    {
        $label = new Label();
        if($id){
            $label = Label::find($id);
            return response()->json($label);
        }
        else{
        $label = $label->orderByDesc('id')->paginate(10);}
        return Response()->json($label);
    }

    public function edit($id = null , EditlabelRequest $request){
        $Label = Label::where('id', $id)->update($request->toArray());
        return response()->json($Label);
    }

    public function userFave(){
        $user = Auth::user();
        $label = $user->product()->whereHas('label');
    }

    public function delete($id){
        $label = Label::destroy($id);
        return response()->json('لیبل با موفقیت حذف شد');
    }
    public function addFave(Request $request){
        $user = Auth::user();
        $exist = DB::table('labelables')->where('user_id',1)
            ->where('labelables_id',$request->labelables_id);
        if ($request->type == 'post'){
            $exist = $exist->where('labelables_type','App\Models\Post')->first();
            if(!$exist){
            $user->labels()->attach($user->id,
                ['user_id' =>$user->id,
                    'labelables_id'=>$request->labelables_id, //id post
                    'labelables_type'=>'App\Models\Post',
                    'label_id'=>'1' //label id favorite
                ]);}else{
                return \response()->json('در لیست شما از قبل وجود دارد');
            }
        }else{
            $exist = $exist->where('labelables_type','App\Models\Product')->first();
            if(!$exist){
                $user->labels()->attach($user->id,
                    ['user_id' =>$user->id,
                        'labelables_id'=>$request->labelables_id, // id product
                        'labelables_type'=>'App\Models\Product',
                        'label_id'=>'1' //label id favorite
                    ]);}else{
                return \response()->json('در لیست شما از قبل وجود دارد');
            }
        }
        return response()->json('به لیست مورد علاقه اضافه شد');
        }


    public function unFave(Request $request){
        $user = Auth::user();
        $product = new product();
        $type = $request->type;
        if($type == 'product'){
            $label =  DB::table('labelables')->where('user_id', $user->id)
                ->where('labelables_id', $request->labelables_id)
                ->where('labelables_type', 'App\Models\Product')
                ->delete();
        }
        if($type == 'post'){
            $label =  DB::table('labelables')->where('user_id', $user->id)
                ->where('labelables_id', $request->labelables_id)
                ->where('labelables_type', 'App\Models\Post')
                ->delete();
        }
        return response()->json("با موفقیت از لیست مورد علاقه حدف شد");
    }
}
