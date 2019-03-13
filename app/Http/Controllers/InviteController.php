<?php

namespace App\Http\Controllers;
use App\User;
use App\Invite;
use App\Mail\InviteCreated;
use Illuminate\Support\Facades\Mail;

use Illuminate\Http\Request;

class InviteController extends Controller
{
    public function invite()
    {
        return view('home');    
    }

    public function process(Request $request)
    {

        do { $token = str_random(); // generate random string 
        } 

        //check if the token already exists and if it does, try again
        while (Invite::where('token', $token)->first());

            //create a new invite record
            $invite = Invite::create([
                'email' => $request->get('email'),
                'token' => $token
            ]);
        
            // send the email
            Mail::to($request->get('email'))->send(new InviteCreated($invite));
                
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

        // create the user with the details from the invite
        User::create(['email' => $invite->email]);

        // delete the invite so it can't be used again
        $invite->delete();

        // here you would probably log the user in and show them the dashboard, but we'll just prove it worked
            // check if they're an existing user
        $existingUser = User::where('email', $user->email)->first();

        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
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
