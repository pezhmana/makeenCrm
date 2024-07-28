<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{


    public function create(Request $request)
    {

        $post = Post::create($request->merge([
            'user_id'=>Auth::user()->id
        ])->toArray());
    if ($request->image) {
        $post->addMediaFromRequest('image')->toMediaCollection('post.image');
    }
        return response()->json($post);}


    public function index($id = null) {
        $post = new Post();
        if($id){
            $post = post::where('id', $id)->first();
            $post->increment('view');
            return Response()->json($post);
        }
        if(Request('search')){
            $search = Request('search');
            $post = Post::where('name','like','%'.$search.'%');
        }
        $post = $post->orderByDesc('id')->paginate(12);
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
