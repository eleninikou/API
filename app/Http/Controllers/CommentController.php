<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Validator;

use Illuminate\Http\Request;
use App\TicketComment;
use App\ProjectActivity;
use App\Ticket;

class CommentController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
                
            $comment = TicketComment::create([
                'comment' => $request->comment,
                'ticket_id' => $request->ticket_id,
                'user_id' => $user->id,
            ]);
            
            $comment_id = $comment->id;
            $ticket = Ticket::find($request->ticket_id);

            
            // Save Project Activity
            $project_activity = ProjectActivity::create([
               'project_id' => $ticket->project_id,      
               'user_id' => $user->id,
               'type' => 'comment',
               'text' => '<p> Commented on ticket: <a href="/home/ticket/'.$ticket->id.'">'.$ticket->title.'</a></p>'
            ]);

            return response()->json(['comment' => $comment, 'message' => 'Comment was created']);
        }

        return response()->json(['message' => 'Could not create comment']);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
