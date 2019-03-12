<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use App\Project;
use Illuminate\Http\Request;

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
        $projects = Project::with('milestones', 'client')->where('creator_id', $user->id)->get();
        return response()->json(['projects' => $projects ]);
    }


    // Create project
    public function store(Request $request)
    {
        $user = Auth::user();
        dd($request);

        $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'client_id' => 'required|numeric'
        ]);

        // $project->name = $request->get('name');
        // $project->description = $request->get('description');
        // $project->creator_id = $user->id;
        // $project->client_id = $request->get('client_id');

        $project = [
            'name' => $request->answer,
            'description' => $request->description,
            'creator_id' => 1,
            'client_id' => $request->client_id
        ];

        Project::create($project);
        return response()->json(['project' => $project, 'message' => 'Project was created']);


    }


    // Show project by id
    public function show($id)
    {
        $project = Project::with('milestones', 'client', 'creator')->find($id);
        return response()->json(['project' => $project]);
    }


    // Edit project
    public function update($id)
    {
        $project = Project::find($id);

        $this->validate(request(), [
            'name' => 'required',
            'description' => 'required',
            'creator_id' => 'required',
        ]);

        $project->name = $request->get('name');
        $project->description = $request->get('description');
        $project->creator_id = $request->get('creator_id');
        $project->client_id = $request->get('client_id');

        $project->save();
        return response()->json(['project' => $project, 'message' => 'Project was updated']);

    }


    // Delete project
    public function destroy($id)
    {
        $project = Project::find($id);
        $project->delete();
        return response()->json(['message' => 'Projected was deleted']);

    }
}
