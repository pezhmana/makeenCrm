<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Order;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function create(CreateCommentRequest $request){
        $hasProduct= Order::where('user_id',Auth::user()->id)->where('product_id',$request->product_id);
        if($hasProduct->exists()){
            $comments = Comment::where('user_id',Auth::user()->id)->where('product_id',$request->product_id);
            if($comments->exists()){
                if($comments->first()->description == null){
                    $comment = $comments->update([
                        'description'=>$request->description,
                        'full_name'=>$request->full_name,
                        'email'=>$request->email,
                    ]);
            }else{
                    $comment =Comment::create($request->merge([
                        'user_id'=>Auth::user()->id,
                        'comment_id'=>0,
                    ])->toArray());
                }
            }else{
                $comment =Comment::create($request->merge([
                    'user_id'=>Auth::user()->id,
                    'comment_id'=>0,
                ])->toArray());
            }
        }else{
            $comment = 'شما دوره را خریداری نکرده اید';
        }

        return response()->json($comment);
    }

    public function index(Request $request){
        $type = $request->type;
        $comment = new Comment();
        $comment = $comment ->withCount(['likes as like_count'=>function (Builder $query)
        {
            $query->where('which','like');
        }])->withCount(['likes as dislike_count'=>function (Builder $query)
        {
            $query->where('which','dislike');
        }]);
        $comment = $comment->with('comments');
        if($request->id){
            $comment = $comment->where('product_id',$request->id);
        }
        $comment = $comment->orderBy('id','DESC')->paginate(10);
        return response()->json([$comment]);
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

    public function like($id = null){
        $user = Auth::user();
        $comment =Comment::find($id);
        $like = Like::where('comment_id',$comment->id)->where('user_id',$user->id);
        if($like->exists()){
            if($like->first()->which == 'dislike'){
                $like->update([
                    'which'=>'like'
                ]);
            }else{
                $like->delete();
            }
        }else{
            $user->likes()->create([
               'which'=>'like',
               'comment_id'=>$comment->id
            ]);
        }
    }

    public function dislike($id = null){
        $user = Auth::user();
        $comment =Comment::find($id);
        $like = Like::where('comment_id',$comment->id)->where('user_id',$user->id);
        if($like->exists()){
            if($like->first()->which == 'like'){
                $like->update([
                    'which'=>'dislike'
                ]);
            }else{
                $like->delete();
            }
        }else{
            $user->likes()->create([
                'which'=>'dislike',
                'comment_id'=>$comment->id
            ]);
        }
    }
}
