<?php

namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Validator;
use App\User;

class PassportController extends Controller
{
    public $successStatus = 200;
    
    public function guard() {
        return Auth::guard('api');
    }

    public function details() { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this->successStatus); 
    } 


    
    public function login(Request $request) {
        // check if they're an existing user
        $existingUser = User::where('email', $request->email)->first();
        if($existingUser){
            // log them in
            auth()->login($existingUser, true);
            $user = Auth::user();
            $success['token'] = $existingUser->createToken('Success')->accessToken;
            $success['user'] = $existingUser;
            return response()->json(["success" => $success]);
        }
        return response()->json(["message" => 'not existing user']);
    }


    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            ]);
            
            if ($validator->fails()) {
                return response()->json(['error'=>$validator->errors()], 401);            
            }
            
            $reg_user = User::create([ 
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'google_id' => ''
            ]);

            $success['token'] =  $reg_user->createToken('Success')->accessToken;
            $success['user'] = $reg_user;

        return response()->json(['success'=>$success], $this->successStatus);
    }



    public function logout() {
        return response()->json(['message' => 'User was logged out.']);
    }



    public function googleAuth(Request $request) {   
        $GoogleAuth = $request;
        // check if they're an existing user
        $existingUser = User::where('email', $GoogleAuth->email)->first();

        if($existingUser){
            auth()->login($existingUser, true);
            $user = Auth::user();
            $success['token'] =  $user->createToken('Login')->accessToken;
            $success['user'] = $user;
            return response()->json(['success' => $success], $this->successStatus);
        } else {
            $new_user = new User;
            $new_user->name = $GoogleAuth->name;
            $new_user->email = $GoogleAuth->email;
            $new_user->google_id = $GoogleAuth->googleId;
            $new_user->avatar = $GoogleAuth->imageUrl;
            $new_user->password = md5(rand(1,10000));
            $new_user->save();
            
            auth()->login($new_user, true);

            $success['token'] =  $new_user->createToken('Success')->accessToken;
            $success['user'] = $new_user;
            return response()->json(['success' => $success], $this->successStatus);
        }
    }

}
