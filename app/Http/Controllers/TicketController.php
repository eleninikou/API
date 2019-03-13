<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{

    // Show all tickets
    public function index()
    {
        $tickets = Ticket::with('type', 'status', 'project', 'creator', 'assignedUser', 'milestone', 'attachments', 'comments')->get();
        return response()->json(['tickets' => $tickets]);
    }

    public function userTickets()
    {
        $user = Auth::user();
        $tickets = Ticket::with('type', 'status', 'project', 'creator', 'assignedUser', 'milestone', 'attachments', 'comments')
        ->where('creator_id', 1)
        ->orWhere('assigned_user_id', 1)
        ->get();

        return response()->json(['tickets' => $tickets]);
    }

    // Create ticket
    public function store(Request $request)
    {
        //
        // TicketAttachment

    }

    // Get ticket by id
    public function show($id)
    {
        $ticket = Ticket::with('type', 'status', 'project', 'creator', 'assignedUser', 'milestone', 'attachments', 'comments')->find($id);
        return response()->json(['ticket' => $ticket]);
    }


    // edit ticket
    public function update(Request $request, Ticket $ticket)
    {
        // TicketAttachment
    }



    // public function adminUpdate(Request $request, Ticket $id)
    // {
    //     $user = Auth::user();
    //     $ticket = Ticket::find($id);
    //     $admin_id = Project::find($ticket->project_id)->pluck('creator_id')->get();

    //     if ($user->id === $admin_id) {
    //         // update everyhting on ticket
    //     }
    // }

    // public function developerUpdate(Request $request, Ticket $id)
    // {
    //     $user = Auth::user();
    //     $ticket = Ticket::find($id);

    //     if ($user->id === $ticket->assigned_user_id) {
    //         // update status
    //     }
    // }

    // public function clientUpdate(Request $request, Ticket $id)
    // {
    //     $user = Auth::user();
    //     $ticket = Ticket::find($id);

    //     if ($user->id === $ticket->creator_id) {
    //         // update title
    //         // description
    //         // type
    //         // priority
    //         // due date
    //     }
    // }


    // delete ticket
    public function destroy($id)
    {
        // $user = Auth::user();
        // $ticket = Ticket::find($id);
        // $admin_id = Project::find($ticket->project_id)->pluck('creator_id')->get();

        // if (($user->id === $ticket->creator_id) || ($user->id === $admin_id)) {
        //     $ticket->attachments()->delete();    
        //     $ticket->delete();
        //     return response()->json(['message' => 'Ticket was deleted']);
        // } else {
        //     return response()->json(['message' => 'You can only delete your own tickets']);

        // }
    }
}
