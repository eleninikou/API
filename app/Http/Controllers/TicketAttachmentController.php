<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\TicketAttachment;

class TicketAttachmentController extends Controller
{

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

    public function updateImages(Request $request, $id)
    {

        // Delete old images
        $images = TicketAttachment::where('ticket_id', $id);
        foreach($images as $image) {
            // Also remove from storage
            $name = basename($image['attachment']);
            Storage::delete('/public/'.substr_replace($name,"",-1));
            $image->delete();
        }
        
        
        $urls = $request->urls;
        // Create new images
        foreach($urls as $url) {
            TicketAttachment::create([
                'ticket_id' => $id,
                'attachment' => $url
            ]);
        }

        $newImages = TicketAttachment::where('ticket_id', $id);


        return response()->json([
            'message' => 'Images updated',
            'images' => $newImages
            ]);
    }


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
