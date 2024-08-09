<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Models\Message;
use App\Models\Teacher;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function create(CreateMessageRequest $request)
    {

        $message = Message::create($request->merge([
            'user_id'=>Auth::user()->id
        ])->toArray());
        return response()->json($message);
    }


    public function index($id = null)
    {
        $message=Ticket::where('user_id',Auth::user()->id)->where('id',$id)->with('messages')->first();
        if(!$message){
            $message='همچین تیکتی وجود ندارد';
        }
        return response()->json($message);
    }

    public function edit(Request $request, $id)
    {
        $message = Message::where('id', $id)->update($request->toArray());
        return response()->json($message);
    }

    public function delete($id)
    {
        $message = Message::where('id', $id)->delete();
        return response()->json($message);
    }
}
