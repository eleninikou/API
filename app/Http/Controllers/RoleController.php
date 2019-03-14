<?php

namespace App\Http\Controllers;
use Validator;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    // Show all Roles
    public function index()
    {
        $roles = Role::get();
        return response()->json(['roles' => $roles ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 

        } else {

            $role = [
                'role' => $request->role,
            ];
                
           Role::create($role);
            return response()->json(['role' => $role, 'message' => 'Role was created']);
        }
        return response()->json(['message' => 'Could not create role']);
    }


    // Show role by id
    public function show($id)
    {
        $role = Role::find($id);
        return response()->json(['role' => $role]);
    }


    public function update(Request $request, $id)
    {
        $role = Role::find($id);

        $validator = Validator::make($request->all(), [
            'role' => 'required',
        ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401); 

            } else {

                $role->role = $request->get('role');
                $role->save();
                return response()->json(['role' => $role, 'message' => 'Role was updated']);
            
            }
      
            return response()->json(['message' => 'Couldn\t update role']);
    }



    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return response()->json(['message' => 'Role was deleted']);

    }

}