<?php

namespace App\Http\Controllers;
use App\User;
use App\Project;
use App\Invite;
use App\Mail\Invitation;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class InviteController extends Controller
{

    public function invite(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        do { $token = str_random(); // generate random string 
        } 

        //check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());

            //create a new invite record
            $invitation = Invite::create([
                'email' => $request->get('email'),
                'token' => $token,
                'project_id' => $project->id,
                'project_name' => $project->name,
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
            //if the invite doesn't exist do something more graceful than this
            abort(404);
        }

        // delete the invite so it can't be used again
        $invite->delete();

            // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();

        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
                $user = Auth::user();
                $success['token'] =  $user->createToken('Login')->accessToken;
                $success['user'] = $user;
                return response()->json(['success' => $success], $this->successStatus);
       
        } else {

            $new_user = new User;
            $new_user->name = $user->name;
            $new_user->email = $user->email;
            $new_user->google_id = $user->id;
            $new_user->password = md5(rand(1,10000));
            $new_user->save();
            
            Auth::loginUsingId($user->id);

            auth()->login($newUser, true);
        }
        return 'Good job! Invite accepted!';
    }
}
