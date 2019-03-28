<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\ProjectActivity;

class ProjectActivityController extends Controller
{

    public function index() {
        $activity = ProjectACtivity::with('project', 'user')->get();
        return response()->json(['activity' => $activity]);
    }

    public function projectActivity() {
        $user = Auth::user();
        $activity = ProjectACtivity::with('project', 'user')->where('user_id', $user->id)->get();
        return response()->json(['activity' => $activity]);
    }

    public function store(Request $request) {
        //
    }


    public function show($id) {
        $activity = ProjectACtivity::with('project', 'user')->find($id);
        return response()->json(['activity' => $activity]);
    }



    public function destroy($id) {
        //
    }
}
