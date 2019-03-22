<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Project;
use App\ProjectUserRole;
use Validator;

class ProjectController extends Controller
{

    // Show all Projects
    public function index()
    {
        $projects = Project::with('milestones', 'client', 'creator')->get();
        return response()->json(['projects' => $projects ]);
    }


    // Show projects created by user
    public function userProjects($id)
    {
        $projects = Project::with('milestones', 'client', 'team', 'creator')->where('creator_id', $id)->get();
        return response()->json(['projects' => $projects ]);
    }

        // Show projects created by user
    public function activeProjects($id)
    {
        $projects = ProjectUserRole::with('project', 'role', 'tickets')->where('user_id', $id)->get();
        return response()->json(['projects' => $projects ]);
    }


    // Create project
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'creator_id' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 
        } else {
            if (!$request->client_id) {
                $client_id = 0;
            } else {
                $client_id = $request->client_id;
            }
                
            $new_project = Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'creator_id' => $request->creator_id,
                'client_id' => $client_id,
            ]);

            $user_role = ProjectUserRole::create([
                'user_id' => $request->creator_id,
                'role_id' => 1,
                'project_id' => $new_project->id
            ]);
            return response()->json(['project' => $new_project, 'user role' => $user_role, 'message' => 'Project was created']);
        }
    }


    // Show project by id
    public function show($id)
    {
        $project = Project::with('milestones', 'client', 'creator')->find($id);
        $team = ProjectUserRole::where('project_id', $id)->with('user')->get();
        return response()->json(['project' => $project, 'team' => $team]);
    }


    // Edit project
    public function update($id, Request $request )
    {
        $project = Project::find($id);
        if ($request->user_id == $project->creator_id) {

            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'description' => 'required',
                'creator_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401); 
            } else {
                $project->name = $request->get('name');
                $project->description = $request->get('description');
                $project->creator_id = $request->get('creator_id');
                $project->client_id = $request->get('client_id');
                $project->save();
                return response()->json(['project' => $project, 'message' => 'Project was updated']);
            }
        } else {
            return response()->json(['message' => 'You can only update projects that you have created']);
        }

    }


    // Delete project
    public function destroy($id)
    {
        $project = Project::find($id);
        if ($project) {
            // if ($user->id == $project->creator_id) {
                $project->delete();
                return response()->json(['message' => 'Projected was deleted']);
            // } else {
            //     return response()->json(['message' => 'You can only delete the projects that you have created']);
            // }
        } else {
            return response()->json(['message' => 'Not valid Project id']);
        }

    }
}
