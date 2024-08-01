<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\EditMessageRequest;
use App\Models\Message;
use App\Models\Teacher;
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
        if ($id) {
            $message = Message::where('id', $id)->first();
        } else {
            $message = Message::orderby('id', 'desc')->paginate(10);
        }
        return response()->json($message);
    }

    public function edit(EditMessageRequest $request, $id)
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
