<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostsRequest;
use App\Models\Post;
use App\Models\Product;
use http\Env\Response;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{


    public function create(CreatePostsRequest $request)
    {

<<<<<<< HEAD
        $post = Post::create($request->toArray());
        if ($request->image) {
            $post->addMediaFromRequest('image')->toMediaCollection('post.image');
        }


        $post = Post::create($request->toArray());
=======
        $post = Post::create($request->merge([
            'user_id'=>Auth::user()->id
        ])->toArray());
>>>>>>> 17f1e9a20520097509839ad5237dee8ad0e18ec1
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
