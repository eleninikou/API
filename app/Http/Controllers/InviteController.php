<?php

namespace App\Http\Controllers;
use App\User;
use App\Project;
use App\Invite;
use App\ProjectUserRole;
use App\Mail\Invitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class InviteController extends Controller
{

    public function invite(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        do { 
            $token = str_random(); // generate random string 
            // $token = Hash::make(str_random());
        } 

        // loop through invitations 
                
        //check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());

            //create a new invite record
            $invitation = Invite::create([
                'email' => $request->get('email'),
                'token' => $token,
                'project_id' => $project->id,
                'project_name' => $project->name,
                'project_role' => $request->get('project_role')
            ]);
        
            // send the email
            Mail::to($request->get('email'))->send(new Invitation($invitation));

            // redirect back where we came from
            return redirect()
                ->back();    
    }
        
    public function accept($token)
    {
        // Look up the invite
        if (!$invite = Invite::where('token', $token)->first()) {
            return response()->json(['message' => 'Seems like you invitation got lost']);
        }

        
        // check if they're an existing user
        $existingUser = User::where('email', $invite->email)->first();        
        if($existingUser){

            // Give role in project
            $user_role = [
                'user_id' => $existingUser->id,
                'role_id' => $invite->project_role,
                'project_id' => $invite->project_id,
            ];
            
            ProjectUserRole::create($user_role);
            
            // Log them in
            auth()->login($existingUser, true);
                $user = Auth::user();
                $success['token'] =  $user->createToken('Login')->accessToken;
                $success['user'] = $user;

                // delete the invite so it can't be used again
                $invite->delete();
                return response()->json(['success' => $success, 'message' => 'invitation accepted']);
       
        } else {

            $new_user = new User;
            $new_user->name = $user->name;
            $new_user->email = $user->email;
            $new_user->google_id = $user->id;
            $new_user->password = md5(rand(1,10000));
            $new_user->save();
            
            $user = User::lastInsertedId();

            $new_user_role = [
                'user_id' => $user->id,
                'role_id' => $invite->project_role,
                'project_id' => $invite->project_id
            ];

            ProjectUserRole::create($user_role);
            $invite->delete();
            auth()->login($newUser, true);

            return response()->json(['message' => 'invitation accepted']);
        }

    }
}
