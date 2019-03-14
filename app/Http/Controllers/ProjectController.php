<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Project;
use Validator;

class ProjectController extends Controller
{

    // Show all Projects
    public function index()
    {
        $projects = Project::with('milestones', 'client', 'creator')->get();
        return response()->json(['projects' => $projects ]);
    }


    // Show projects for a specific user
    public function userProjects()
    {
        $user = Auth::user();
        $projects = Project::with('milestones', 'client', 'team')->where('creator_id', $user->id)->get();
        return response()->json(['projects' => $projects ]);
    }


    // Create project
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'creator_id' => 'required',
            'client_id' => 'required|numeric'
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 

        } else {

            $project = [
                'name' => $request->name,
                'description' => $request->description,
                'creator_id' => $request->creator_id,
                'client_id' => $request->client_id,
            ];
                
            Project::create($project);
            return response()->json(['project' => $project, 'message' => 'Project was created']);
        }
    }


    // Show project by id
    public function show($id)
    {
        $project = Project::with('milestones', 'client', 'creator', 'team')->find($id);
        return response()->json(['project' => $project]);
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
                'client_id' => 'required',
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
        $user = Auth::user();
        $project = Project::find($id);

        if ($user->id == $project->creator_id) {
            $project->delete();
            return response()->json(['message' => 'Projected was deleted']);
        } else {
            return response()->json(['message' => 'You can only delete the projects that you have created']);
        }

    }
}
