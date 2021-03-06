<?php

    namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;

    class UserController extends Controller
    {
        // Get all users
        public function index() {
            $users = User::get();
            return response()->json(['users' => $users ]);
        }


        // Get users by id
        public function show($id) {
            $user = User::find($id);
            return response()->json(['user' => $user]);
        }

        public function update(Request $request, $id)
        {
            $user = User::find($id);
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'email' => 'required|string|max:255',
                ]);

                if ($validator->fails()) {
                    return response()->json(['error'=>$validator->errors()], 401); 
                } else {
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->avatar = $request->avatar;

                    if($request->get('password')){
                        $user->password = bcrypt($request->password);
                    }
                    $user->save();
                    return response()->json(['user' => $user, 'message' => 'Your profile is updated!']);
                
                }
          
                return response()->json(['message' => 'Couldn\'t update user']);
        }

        public function deleteAccount($id) {
            $user = User::find($id);
            $user->email = '';
            $user->password = '';
            $user->avatar = '';
            $user->deleted = true;
            $user->save();
    
            return response()->json(['message' => 'Your account is now deleted!']);
        }

        
        // Delete user
        public function destroy($id)
        {

        }
    }
