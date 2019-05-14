<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\ProjectActivity;
use App\ProjectUserRole;

class ProjectActivityController extends Controller
{

    public function index() {
        $activity = ProjectActivity::with('project', 'user')->get();
        return response()->json(['activity' => $activity]);
    }

    public function projectActivity() {
        $user = Auth::user();
        $projects = ProjectUserRole::where('user_id', $user->id)->pluck('project_id');
        $activities = [];
        foreach($projects as $project) {
            $act = ProjectActivity::with('project', 'user')->where('project_id', $project)->orderBy('created_at', 'desc')->take(50)->get();
            array_push($activities, $act);
        }

        $activity = asort($activityies);
        return response()->json(['activity' => $activity]);
    }

    public function store(Request $request) {
        //
    }


    public function show($id) {
        $activity = ProjectActivity::with('project', 'user')->find($id);
        return response()->json(['activity' => $activity]);
    }

    public function destroy($id) {
        //
    }
}
