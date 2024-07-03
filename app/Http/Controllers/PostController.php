<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create(Request $request)
    {

        $post = post::create($request->toArray());
        return response()->json($post);}


    public function index($id = null) {
        if($id){
            $post = post::where('id', $id)->first();
        }
        else{
            $post = post::orderby('id', 'desc')->paginate(3);
        }
        return response()->json($post);
    }
    public function edit(Request $request, $id){
        $post = post::where('id', $id)->update($request->toArray());
        return response()->json($post);
    }
    public function delete($id){
        $post = post::where('id',  $id)->delete();
        return response()->json($post);
    }
}
