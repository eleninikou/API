<?php

namespace App\Http\Controllers;

use App\Milestone;
use Illuminate\Http\Request;

class MilestoneController extends Controller
{

    // Get all milestones
    public function index()
    {
        $milestones = Milestone::with('project', 'tickets')->get();
        return response()->json(['milestones' => $milestones ]);
    }

    // Get all milestones for one project
    public function project($id)
    {
        $milestones = Milestone::with('project', 'tickets')->where('project_id', $id)->get();
        return response()->json(['milestones' => $milestones ]);
    }

    // Create milestone 
    public function store(Request $request)
    {
        //
    }


    // Show milestone by id
    public function show($id)
    {
        $milestone = Milestone::with('project', 'tickets')->find($id);
        return response()->json(['milestone' => $milestone]);
    }


    // Edit milestone
    public function update(Request $request, Milestone $milestone)
    {
        //
    }

    // Delete milestone
    public function destroy(Milestone $milestone)
    {
        //
    }
}
