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

    public function invite(Request $request, $id) {
        
        $project = Project::findOrFail($id);
        $invitation = Invite::where([['email', $request->get('email')], ['project_id', $id]])->get();

        if($invitation) {
            return response()->json(['message' => 'This user has allready been invited']);
        } else {

        do {  $token = str_random();  } 
        // loop through invitations 
        //check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());

            //create a new invite record
            $new_invitation = Invite::create([
                'email' => $request->get('email'),
                'token' => $token,
                'project_id' => $project->id,
                'project_name' => $project->name,
                'project_role' => $request->get('project_role')
            ]);

            if ($new_invitation) {
                // send the email
                Mail::to($request->get('email'))->send(new Invitation($new_invitation));
                return response()->json(['message' => 'The invitation was successfully sent', 'inviation' => $new_invitation]);
            } else {
                return response()->json(['message' => 'Could not send invitation']);

            }
        
        }
    }
        
    public function accept($token){
        // Look up the invite
        $invite = Invite::where('token', $token)->first();
        if (!$invite) {
            return response()->json(['message' => 'Seems like you invitation got lost']);
        }

        // check if they're an existing user
        $existingUser = User::where('email', $invite->email)->first();        
        if($existingUser){

            // Give role in project
            ProjectUserRole::create([
                'user_id' => $existingUser->id,
                'role_id' => $invite->project_role,
                'project_id' => $invite->project_id,
            ]);

            if($invite->project_role === 3) {
                $project = Project::find($invite->project_id);
                $project->client_id = $existingUser->id;
                $project->save();
            }

            // Log them in
            auth()->login($existingUser, true);
                $user = Auth::user();
                $success['token'] =  $user->createToken('Login')->accessToken;
                $success['user'] = $user;

                // delete the invite so it can't be used again
                $invite->delete();
                return response()->json(['success' => $success, 'message' => 'invitation accepted']);
       
        } else {
            // $new_user = new User;
            // $new_user->name = $user->name;
            // $new_user->email = $user->email;
            // $new_user->google_id = $user->id;
            // $new_user->password = md5(rand(1,10000));
            // $new_user->save();
            // $user = User::lastInsertedId();

            // ProjectUserRole::create([
            //     'user_id' => $user->id,
            //     'role_id' => $invite->project_role,
            //     'project_id' => $invite->project_id
            // ]);

            // $invite->delete();
            // auth()->login($newUser, true);
            return response()->json([ 'message' => 'You need to register']);


            // return response()->json(['message' => 'invitation accepted']);
        }

    }

    
    public function usersInvited($id) {
        $emails = Invite::where('project_id', $id)->pluck('email');
        return response()->json(['emails' => $emails, 'message' => 'Emails']);
    }


    // Get email
    public function getEmail($token) {

        $email = Invite::where('token', $token)->pluck('email');

        if($email) {
            $existingUser = User::where('email', $email)->first();
    
            if ($existingUser) {
                $existing = true;
            } else {
                $existing = false;
            }
            
            return response()->json(['email' => $email, 'existing' => $existing ]);
        } else {
            return response()->json(['message' => 'Your invitation has expired' ]);
        }


    }
}
