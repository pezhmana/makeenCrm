<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create(Request $request)
    {

        $ticket = Ticket::create($request->toArray());
        return response()->json($ticket);
    }


    public function index($id = null)
    {
        if ($id) {
            $ticket = Ticket::where('id', $id)->first();
        } else {
            $ticket = Ticket::orderby('id', 'desc')->paginate(10);
        }
        return response()->json($ticket);
    }

    public function edit(Request $request, $id)
    {
        $ticket = Ticket::where('id', $id)->update($request->toArray());
        return response()->json($ticket);
    }

    public function delete($id)
    {
        $ticket = Ticket::where('id', $id)->delete();
        return response()->json($ticket);
    }
}
