<?php

namespace App\Http\Controllers;
use Validator;
use App\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    // Get all types
    public function index()
    {
        {
            $ticket_types = TicketType::get();
            return response()->json(['types' => $ticket_types]);
        }
    }

    // Create new type 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 

        } else {

            $type = [
                'type' => $request->type,
            ];
                
           TicketType::create($type);
            return response()->json(['type' => $type, 'message' => 'Type was created']);
        }
        return response()->json(['message' => 'Could not create Ticket type']);
    }

    // Get type by id
    public function show($id)
    {
        $type = TicketType::find($id);
        return response()->json(['type' => $type]);
    }

    // Update type
    public function update(Request $request, $id)
    {
        $type = TicketType::find($id);

        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401); 

            } else {

                $type->type = $request->get('type');
                $type->save();
                return response()->json(['type' => $type, 'message' => 'Ticket type was updated']);
            
            }
      
            return response()->json(['message' => 'Couldn\t update Ticket type']);
    }

    // Delete type
    public function destroy($id)
    {
        $type = TicketType::find($id);
        $type->delete();
        return response()->json(['message' => 'Ticket type was deleted']);
    }
}
