<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;

class LoginController extends Controller
{

public $successStatus = 200;

  public function redirectToProvider()
  {
      return Socialite::driver('google')->redirect();
  }


/**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return response()->json(['error' => 'could not log in user']);
        }

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
            
            auth()->login($new_user, true);
            return response()->json(['success' => $success], $this->successStatus);

        }

    }

    public function GoogleLogin(Request $request)
    {   
        $GoogleAuth = $request;

        // check if they're an existing user
        $existingUser = User::where('email', $GoogleAuth->email)->first();

        if($existingUser){
            // log them in
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
            $new_user->password = md5(rand(1,10000));
            $new_user->save();
            
            auth()->login($new_user, true);
            return response()->json(['success' => $success], $this->successStatus);
        }
    }


    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
