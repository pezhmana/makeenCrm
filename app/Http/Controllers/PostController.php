<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostsRequest;
use App\Http\Requests\EditPostsRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index($id = null){
        if($id) {
            $posts = post::where('id', $id)->first();

            $posts = $posts->OrderBy('id', 'DESC')->paginate(10);


            return response()->json($posts);
        }
    }
    public function create(CreatePostsRequest $request){
        $posts = Post::create($request->toArray());
        $posts->products()->attach($request->products_id);
        return response()->json($posts);
    }

    public function edit(EditPostsRequest $request , $id){
        $posts = Post::find($id);
        $posts->update($request->all());
        return response()->json($posts);
    }
    public function delete($id)
    {
        $posts = Post::destroy($id);
        return response()->json($posts);

    }
}
