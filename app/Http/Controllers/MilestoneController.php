<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Milestone;
use App\Ticket;
use App\Project;
use App\ProjectActivity;

class MilestoneController extends Controller
{
    // Get all milestones
    public function index() {
        $milestones = Milestone::with('project', 'tickets')->get();
        return response()->json(['milestones' => $milestones ]);
    }

    // Get all milestones for one project
    public function project($id) {
        $milestones = Milestone::with('project', 'tickets')->where('project_id', $id)->get();
        return response()->json(['milestones' => $milestones ]);
    }

    // Create milestone 
    public function store(Request $request) {

        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'project_id' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 

        } else {
                
            $milestone = Milestone::create([
                'title' => $request->title,
                'focus' => $request->focus,
                'project_id' => $request->project_id,
                'due_date' => $request->due_date,
            ]);
            
            $milestone_id = $milestone->id;
        
            // Save Project Activity
            $project_activity = ProjectActivity::create([
               'project_id' => $request->project_id,      
               'user_id' => $user->id,
               'type' => 'milestone',
               'text' => '<p>created a new milestone: <a href="/home/milestone/'.$milestone_id.'">'.$request->title.'</a></p>'
            ]);

            return response()->json(['milestone' => $milestone, 'message' => 'Milestone was created']);
        }
        return response()->json(['message' => 'Could not create milestone']);

    }


    // Show milestone by id
    public function show($id) {
        $milestone = Milestone::with('project', 'tickets')->find($id);
        $tickets = Ticket::where('milestone_id', $id)->with('creator', 'assignedUser', 'status', 'type')->get();
        return response()->json(['milestone' => $milestone, 'tickets' => $tickets]);
    }


    // Edit milestone
    public function update(Request $request, $id)
    {
        $milestone = Milestone::find($id);
        $project = Project::find($milestone->project_id);
        $user = Auth::user();


        if ($user->id == $project->creator_id) {

            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'project_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401); 
            } else {
                $milestone->title = $request->get('title');
                $milestone->focus = $request->get('focus');
                $milestone->project_id = $request->get('project_id');
                $milestone->due_date = $request->get('due_date');
                $milestone->save();
                return response()->json(['milestone' => $milestone, 'message' => 'Milestone was updated']);
            }
        } else {
            return response()->json(['message' => 'You can only update milestones in projects that you have created']);
        }
    }

    // Delete milestone
    public function destroy($id) {

        $user = Auth::user();
        $milestone = Milestone::find($id);
        $project = Project::find($milestone->project_id);

        if ($user->id == $project->creator_id) {
            $milestone->tickets()->delete();    
            $milestone->delete();
            return response()->json(['message' => 'Milestone was deleted']);
        } else {
            return response()->json(['message' => 'You can only delete milestones in projects that you have created']);
        }

    }
}
