<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function create(CreateCommentRequest $request){

        $type = $request->type;
        if($type == 'post'){
          $comment =Comment::create($request->merge([
              'commentable_type'=>'App\Models\Post',
              'user_id'=>Auth::user()->id,
              'comment_id'=>0
              ])->toArray());
        }
        if($type == 'product'){
            $comment = Comment::create($request->merge([
                'commentable_type'=>'App\Models\Product',
                'user_id'=>Auth::user()->id,
                'comment_id'=>0
            ])->toArray());
        }
        return response()->json($comment);
    }

    public function index( CreateCommentRequest $request){
        $type = $request->type;
        $comment = new Comment();
        $comment = $comment->with('comments');
        if($type == 'post'){
            $comment = $comment->where('commentable_type','App\Models\Post');
        }
        if($type == 'product'){
            $comment = $comment->where('commentable_type','App\Models\Product');
        }
        if($request->id){
            $comment = $comment->where('commentable_id',$request->id);
        }
        $comment = $comment->orderBy('id','DESC')->paginate(10);
        return response()->json($comment);
    }

    public function delete($id){
        Comment::destroy($id);
        return response()->json("کاربر با موفقیت حذف شد");
    }

    public function answer(Request $request){
        $comment_id = $request->comment_id;
        $comment = Comment::create($request->merge([
            'comment_id'=>$comment_id,
            'user_id'=>Auth::user()->id,
            'commentable_type'=>Comment::find($comment_id)->first()->commentable_type,
            'commentable_id'=>Comment::find($comment_id)->first()->commentable_id
        ])->toArray());
        return response()->json($comment);
    }
}
