<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\EditTicketRequest;
use App\Models\Teacher;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function create(CreateTicketRequest $request)
    {
        $user = Auth::user();

        $ticket = Ticket::create($request->merge([
            'user_id'=>$user->id
        ])->toArray());
        return response()->json($ticket);
    }


    public function index($id = null)
    {
        $user = Auth::user();
        if ($id) {
            $ticket = Ticket::where('id', $id)->first();
        } else {
            $ticket = Ticket::orderby('id', 'desc')->paginate(10);

        }
        return response()->json($ticket);
    }

    public function edit(EditTicketRequest $request, $id)
    {
        $ticket = Ticket::where('id', $id)->update($request->toArray());
        return response()->json($ticket);
    }

    public function delete($id)
    {
        $ticket = Ticket::where('id', $id)->delete();
        return response()->json($ticket);
    }

    public function userTicket()
    {
        $user = Auth::user();
        $ticket = new Ticket();
        $ticket = $user->tickets()->get();
        $countOpen = $user->tickets()->where('status','open')->count();
        $countRun = $user->tickets()->where('status','running')->count();
        $countClose = $user->tickets()->where('status','closed')->count();
        $countAnswer = $user->tickets()->where('status','answered')->count();
        $counts = [
            'open' => $countOpen,
            'running' => $countRun,
            'closed' => $countClose,
            'answered' => $countAnswer,
        ];

        return response()->json([
            'tickets' => $ticket,
            'counts' => $counts,
        ]);

    }
}
