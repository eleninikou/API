<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\TicketAttachment;

class TicketAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $image = $request->file;
        $input['imagename'] = time();
        $name = $image->getClientOriginalName();


        $url = Storage::disk('uploads')->put('/', $image);
        
        if ($url) {
            return response()->json(['url' => '/storage/'.$url]);
        } else {
            return response()->json(['message', 'could not get url']);
        }

        return response()->json(['image', $image]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TicketAttachment  $ticketAttachment
     * @return \Illuminate\Http\Response
     */
    public function show(TicketAttachment $ticketAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TicketAttachment  $ticketAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketAttachment $ticketAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TicketAttachment  $ticketAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TicketAttachment $ticketAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TicketAttachment  $ticketAttachment
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $image = TicketAttachment::find($id);
        $ticket_id = TicketAttachment::find($id)->pluck('ticket_id');
        $rest = TicketAttachment::where('ticket_id', $ticket_id);
        if ($image) {
            $image->delete();
            return response()->json(['message' => 'Image is deleted', 'images' => $rest]);
        } else {
            return response()->json(['message' => 'error', $request]);

        }
 

    }
}
