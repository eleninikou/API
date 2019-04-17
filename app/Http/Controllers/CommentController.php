<?php
namespace App\Http\Controllers;

use Validator;
use App\Ticket;
use App\TicketComment;
use App\ProjectActivity;
use App\CommentAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{

    // Get all comments
    public function index()
    {
        $comments = TicketComments::with('user', 'attachments')->get();
        return response()->json(['comments' => $milestones ]);
    }

    // Save comment
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

            // Make array from editor to string
            $comment = serialize($request->comment);
                
            // Save comment
            $comment = TicketComment::create([
                'comment' => $comment,
                'ticket_id' => $request->ticket_id,
                'user_id' => $user->id,
            ]);
            
            // Save images from uploader
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

    // Show on Comment
    public function show($id)
    {
        $comment = TicketComment::with('user', 'attachments')->find($id);
        return response()->json(['comment' => $comment]);
    }


    // Delete comment
    public function destroy($id)
    {
        $user = Auth::user();
        $comment = TicketComment::find($id);
        $images = CommentAttachment::where('comment_id', $id)->get();

        // Delete images from storage
        foreach($images as $image) {
            $name = basename($image->attachment);
            Storage::delete('/public/'.$name);
            $image->delete();
        }

        // Delete attachments and comment
        if ($user->id == $comment->user_id) {
            $comment->attachments()->delete();    
            $comment->delete();
            return response()->json(['message' => 'Your comment was deleted!', 'images', $images]);
        } else {
            return response()->json(['message' => 'You can only delete your own comments']);
        }
    }
}
