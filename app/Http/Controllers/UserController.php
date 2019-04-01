<?php

    namespace App\Http\Controllers;

    use App\User;
    use Illuminate\Http\Request;
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

        
        // Delete user
        public function destroy($id)
        {
            // Delete projects connected to user
        }
    }
