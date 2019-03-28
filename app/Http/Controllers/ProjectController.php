<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Project;
use App\Ticket;
use App\ProjectUserRole;
use App\ProjectActivity;

use Validator;

class ProjectController extends Controller{

    // Show all Projects
    public function index() {
        $projects = Project::with('milestones', 'client', 'creator')->get();
        return response()->json(['projects' => $projects ]);
    }

    // Show projects created by user
    public function userProjects($id) {
        $projects = Project::with('milestones', 'client', 'creator')->where('creator_id', $id)->get();
        return response()->json(['projects' => $projects ]);
    }

    // Show projects created by user
    public function activeProjects() {
        $user = Auth::user();
        $projects = ProjectUserRole::with('project', 'role', 'tickets', 'milestones')->where('user_id', $user->id)->get();
        return response()->json(['projects' => $projects ]);
    }

    // Show projects created by user
    public function team($id) {
        $team = ProjectUserRole::with('user', 'role')->where('project_id', $id)->get();
        return response()->json(['team' => $team ]);
    }


    // Create project
    public function store(Request $request) {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 
        } else {
            // If project is without client
            if (!$request->client_id) { 
                $client_id = 0;
            } else { 
                $client_id = $request->client_id; 
            }
            
            $user = Auth::user();

            // Create project
            $new_project = Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'creator_id' => $user->id,
                'client_id' => $client_id,
            ]);

            // Attach user role
            $user_role = ProjectUserRole::create([
                'user_id' => $user->id,
                'role_id' => 1,
                'project_id' => $new_project->id
            ]);

            return response()->json(['project' => $new_project, 'user role' => $user_role, 'message' => 'Project was created']);
        }
        return response()->json(['message' => 'Something went wrong']);

    }


    // Show project by id
    public function show($id) {
        $project = Project::with('milestones', 'client', 'creator', 'tickets')->find($id);
        $team = ProjectUserRole::where('project_id', $id)->with('user', 'role')->get();
        $tickets = Ticket::where('project_id', $id)->with('creator', 'assignedUser', 'status', 'type')->get();
        return response()->json(['project' => $project, 'team' => $team, 'tickets' => $tickets]);
    }


    // Edit project
    public function update($id, Request $request ) {
        
        $project = Project::find($id);
        $user = Auth::user();

        if ($user->id == $project->creator_id) {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401); 
            } else {
                $project->name = $request->get('name');
                $project->description = $request->get('description');
                $project->creator_id = $user->id;
                $project->client_id = $request->get('client_id');
                $project->save();
                return response()->json(['project' => $project, 'message' => 'The project was updated!']);

                // Save Project Activity
                $project_activity = ProjectActivity::create([
                    'project_id' => $id,        
                    'user_id' => $user->id,
                    'type' => 'updated project'
                ]);
            }
        } else {
            return response()->json(['message' => 'You can only update projects that you have created']);
        }

    }


    // Delete project
    public function destroy($id){
        $project = Project::find($id);
        $user = Auth::user();
        if ($project) {
            if ($user->id == $project->creator_id) {
                $project->delete();
                return response()->json(['message' => 'Projected was deleted']);
            } else {
                return response()->json(['message' => 'You can only delete the projects that you have created']);
            }
        } else {
            return response()->json(['message' => 'Not valid Project id']);
        }

    }
}
