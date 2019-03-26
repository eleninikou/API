<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Ticket;
use App\Project;
use Validator;

class TicketController extends Controller
{
    // Show all tickets
    public function index() {
        $tickets = Ticket::with('type', 'status', 'project', 'creator', 'assignedUser', 'milestone', 'attachments', 'comments')->get();
        return response()->json(['tickets' => $tickets]);
    }

    public function userTickets() {
        $user = Auth::user();
        $tickets = Ticket::with('type', 'status', 'project', 'creator', 'assignedUser', 'milestone', 'attachments', 'comments')
        ->where('creator_id', $user->id)
        ->orWhere('assigned_user_id', $user->id)
        ->get();

        return response()->json(['tickets' => $tickets]);
    }

    // Create ticket
    public function store(Request $request) {
        $user = Auth::user();

        // TicketAttachment - fix!!
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'type_id' => 'required',
            'status_id' => 'required',
            'project_id' => 'required',
            'priority' => 'required',
            'due_date' => 'required',
            'milestone_id' => 'required'
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 

        } else {
            $ticket = Ticket::create([
                'title' => $request->title,
                'description' => $request->description,
                'type_id' => $request->type_id,
                'status_id' => $request->status_id,
                'project_id' => $request->project_id,
                'priority' => $request->priority,
                'due_date' => $request->due_date,
                'creator_id' => $user->id,
                'assigned_user_id' => $request->assigned_user_id,
                'milestone_id' => $request->milestone_id
            ]);
            return response()->json(['ticket' => $ticket, 'message' => 'Ticket was created']);
        }

    }

    // Get ticket by id
    public function show($id) {
        $ticket = Ticket::with('type', 'status', 'project', 'creator', 'assignedUser', 'milestone', 'attachments', 'comments')->find($id);
        return response()->json(['ticket' => $ticket]);
    }


    // edit ticket
    public function update(Request $request, $id) {
        // TicketAttachmet - fix!
        $ticket = Ticket::find($id);
        $user = Auth::user();

        if ($user->id == $ticket->creator_id) {
            $ticket->title = $request->title;
            $ticket->description = $request->description;
            $ticket->type_id = $request->type_id;
            $ticket->status_id = $request->status_id;
            $ticket->project_id = $request->project_id;
            $ticket->priority = $request->priority;
            $ticket->due_date = $request->due_date;
            $ticket->creator_id = $user->id ;
            $ticket->assigned_user_id = $request->assigned_user_id;
            $ticket->milestone_id = $request->milestone_id;

            $ticket->save();
            return response()->json(['ticket' => $ticket, 'message' => 'Ticket was updated']);
            
        } else {
            return response()->json(['message' => 'You cant make any changes on this ticket' ]);
        }

    }


    // delete ticket
    public function destroy($id)
    {
        $user = Auth::user();
        $ticket = Ticket::find($id);
        $project = Project::find($ticket->project_id);

        if (($user->id == $ticket->creator_id) || ($user->id == $project->creator_id)) {
            $ticket->attachments()->delete();    
            $ticket->delete();
            return response()->json(['message' => 'Ticket was deleted']);
        } else {
            return response()->json(['message' => 'You can only delete your own tickets']);
        }
    }
}
