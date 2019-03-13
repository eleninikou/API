<?php

namespace App\Http\Controllers;

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
        //
    }

    // Get type by id
    public function show($id)
    {
        $type = TicketType::find($id);
        return response()->json(['type' => $type]);
    }

    // Update type
    public function update(Request $request, Tickettype $tickettype)
    {
        //
    }

    // Delete type
    public function destroy(Tickettype $id)
    {
        $type = TicketType::find($id);
        $type->delete();
        return response()->json(['message' => 'Ticket type was deleted']);
    }
}
