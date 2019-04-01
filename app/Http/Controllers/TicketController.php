<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Ticket;
use App\TicketStatus;
use App\TicketComment;
use App\Milestone;
use App\Project;
use App\ProjectActivity;
use App\ProjectUserRole;
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
        ->orderBy('due_date', 'asc')
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

            // Save Project Activity
            $project_activity = ProjectActivity::create([
                'project_id' => $request->project_id,      
                'user_id' => $user->id,
                'type' => 'ticket',
                'text' => '<p>created a new ticket: <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> </p>'
             ]);

            if ($project_activity) {
                return response()->json(['ticket' => $ticket, 'message' => 'Ticket was created']);
            } else {
                return response()->json(['ticket' => $ticket, 'message' => 'Could not update activity feed']);
            }
        }

    }

    // Get ticket by id
    public function show($id) {
        $ticket = Ticket::with('type', 'status', 'project', 'creator', 'assignedUser', 'milestone', 'attachments', 'comments')->find($id);
        $comments = TIcketComment::where('ticket_id', $ticket->id)->with('user')->orderBy('created_at', 'desc')->get();
        $team = ProjectUserRole::with('user', 'role')->where('project_id', $ticket->project_id)->get();
        $milestones = Milestone::where('project_id', $ticket->project_id)->get();
        return response()->json(['ticket' => $ticket, 'team' => $team, 'milestones' => $milestones, 'comments' => $comments]);
    }


    // edit ticket
    public function update(Request $request, $id) {
        // TicketAttachmet - fix!
        $ticket = Ticket::find($id);
        $ticket_status = TicketStatus::find($ticket->status_id);
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
            
            // If status has changed
            if ($ticket_status->id !== $ticket->status_id) {
                switch ($request->status_id) {
                    case 1:
                    $text = '<p>changed status on <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> from "'.$ticket_status->status.'" to "To do" </p>';
                    break;
                    case 2:
                    $text = '<p>changed status on <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> from "'.$ticket_status->status.'" to "In progress" </p>';
                    break;
                    case 3:
                    $text = '<p>changed status on <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> from "'.$ticket_status->status.'" to "Review" </p>';
                    break;
                    case 4:
                    $text = '<p>changed status on <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> from "'.$ticket_status->status.'" to "Completed" </p>';
                    break;     
                    case 5:
                    $text = '<p>changed status on <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> from "'.$ticket_status->status.'" to "On hold" </p>';
                    break; 
                    case 6:
                    $text = '<p>changed status on <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> from "'.$ticket_status->status.'" to "To be discussed" </p>';
                    break; 
                    case 7:
                    $text = '<p>changed status on <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a> from "'.$ticket_status->status.'" to "Archived" </p>';
                    break;                         
                    default:
                    return;
                }
            }
            
            // Save Project Activity
            $project_activity = ProjectActivity::create([
                'project_id' => $request->project_id,      
                'user_id' => $user->id,
                'type' => 'ticket',
                'text' => $text
                ]);

            return response()->json(['ticket' => $ticket, 'message' => 'Ticket was updated', 'text' => $text]);
                
        } else {
            return response()->json(['message' => 'You cant make any changes on this ticket' ]);
        }

    }


    // delete ticket
    public function destroy($id)
    {
        $user = Auth::user();
        $ticket = Ticket::find($id);
        $ticket_title = $ticket->title;
        $project = Project::find($ticket->project_id);

        if (($user->id == $ticket->creator_id) || ($user->id == $project->creator_id)) {
            $ticket->attachments()->delete();    
            $ticket->delete();

            // Save Project Activity
            $project_activity = ProjectActivity::create([
                'project_id' => $project->id,      
                'user_id' => $user->id,
                'type' => 'ticket',
                'text' => '<p> deleted ticket: '.$ticket->title.' </p>'
                ]);

            return response()->json(['message' => 'Ticket was deleted']);
        } else {
            return response()->json(['message' => 'You can only delete your own tickets']);
        }
    }
}
