<?php

namespace App\Http\Controllers;

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
        //
    }

    // Get status by id
    public function show($id)
    {
        $status = TicketStatus::find($id);
        return response()->json(['status' => $status]);
    }

    // Update status
    public function update(Request $request, TicketStatus $ticketStatus)
    {
        //
    }

    // Delete status
    public function destroy(TicketStatus $id)
    {
        $status = TicketStatus::find($id);
        $status->delete();
        return response()->json(['message' => ' Ticket Status was deleted']);
    }
}
