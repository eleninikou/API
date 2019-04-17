<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Ticket;
use App\TicketComment;
use App\ProjectActivity;
use App\CommentAttachment;

class CommentController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {


    }


    public function store(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'ticket_id' => 'required',
        ]);
            
        if ($validator->fails()) {

            return response()->json(['error'=>$validator->errors()], 401); 

        } else {

            $comment = serialize($request->comment);
                
            $comment = TicketComment::create([
                'comment' => $comment,
                'ticket_id' => $request->ticket_id,
                'user_id' => $user->id,
            ]);
            
            $urls = $request->images;
            foreach($urls as $url) {
                CommentAttachment::create([
                    'comment_id' => $comment->id,
                    'attachment' => $url
                ]);
            }

            $comment_id = $comment->id;
            $ticket = Ticket::find($request->ticket_id);

            // Save Project Activity
            $project_activity = ProjectActivity::create([
               'project_id' => $ticket->project_id,      
               'user_id' => $user->id,
               'type' => 'comment',
               'text' => '<p> Commented on ticket: <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a></p>'
            ]);

            return response()->json(['comment' => $comment, 'message' => 'Your comment is published!']);
        }

        return response()->json(['message' => 'Could not create comment']);

    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $comment = TicketComment::find($id);

        if ($user->id == $comment->user_id) {
            $comment->delete();
            return response()->json(['message' => 'Your comment was deleted!']);
        } else {
            return response()->json(['message' => 'You can only delete your own comments']);
        }
    }
}
