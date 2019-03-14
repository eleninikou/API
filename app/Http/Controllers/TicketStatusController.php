<?php

namespace App\Http\Controllers;
use Validator;
use App\TicketStatus;
use Illuminate\Http\Request;

class TicketStatusController extends Controller
{
    
    // Get all status
    public function index()
    {
        {
            $ticket_status = TicketStatus::get();
            return response()->json(['status' => $ticket_status]);
        }
    }

    // Create new status 
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);
            
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401); 

        } else {

            $status = [ 'status' => $request->status];
            TicketStatus::create($status);
            return response()->json(['status' => $status, 'message' => 'Status was created']);
        }
        return response()->json(['message' => 'Could not create status']);
    }

    // Get status by id
    public function show($id)
    {
        $status = TicketStatus::find($id);
        return response()->json(['status' => $status]);
    }

    // Update status
    public function update(Request $request, $id)
    {
        $status = TicketStatus::find($id);

        $validator = Validator::make($request->all(), [
            'status' => 'required',
        ]);

            if ($validator->fails()) {

                return response()->json(['error'=>$validator->errors()], 401); 

            } else {

                $status->status = $request->get('status');
                $status->save();
                return response()->json(['status' => $status, 'message' => 'Status was updated']);
            
            }
      
            return response()->json(['message' => 'Couldn\t update status']);
    }

    // Delete status
    public function destroy($id)
    {
        $status = TicketStatus::find($id);
        $status->delete();
        return response()->json(['message' => ' Ticket Status was deleted']);
    }
}
