<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        $attachmentPath = null;

        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('message_attachments', 'public');
        }
        $message = Message::store($request, $attachmentPath);

        return response()->json([
            'message' => 'Message created successfully!',
            'data' => [
                'message' => new MessageResource($message)
            ]
        ]);
    }

    public function update(Request $request)
    {
        $message = data_get($request, 'message');

        $message->update([
            'message' => $request->new_message
        ]);

        return response()->json([
            'message' => 'Message updated successfully!',
            'data' => [
                'message' => new MessageResource($message)
            ]
        ]);
    }

    public function delete(Request $request)
    {
        $message = $request->message;
        $message->delete();

        return response()->json([
            'message' => 'Message deleted successfully!'
        ]);
    }

    public function read(Request $request)
    {
        $channelId = $request->channel_id;
        $messages = Message::where('channel_id', $channelId)->get();

        return response()->json([
            'message' => 'Messages fetched successfully!',
            'data' => [
                'messages' => MessageResource::collection($messages)
            ]
        ]);
    }
}
