<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();

        $channel = Channel::store($user, $request);
        return response()->json([
            'message' => 'Channel created successfully!',
            'data' => [
                'name' => $request->name,
                'type' => $request->type,
                'company_id' => $request->company_id,
                'channel' => $channel
            ]
        ]);
    }

    public function update(Request $request)
    {
        $channel = $request->channel;
        $newName = $request->name;

        $channel = Channel::edit($channel, $newName);


        return response()->json([
            'message' => 'Channel updated successfully!',
            'data' => [
                'channel' => $channel
            ]
        ]);
    }

    public function delete(Request $request)
    {
        $channel = $request->channel;
        $channel->delete();

        return response()->json([
            'message' => 'Channel deleted successfully!'
        ]);
    }


    public function assignMember(Request $request)
    {
        $userIds = data_get($request, 'user_ids');
        $channel = data_get($request, 'channel');

        $channel->users()->syncWithoutDetaching($userIds);

        return response()->json([
            'message' => 'User assigned as member successfully!',
        ]);
    }

    public function removeMember(Request $request)
    {
        $userIds = data_get($request, 'user_ids');
        $channel = data_get($request, 'channel');

        $channel->users()->detach($userIds);

        return response()->json([
            'message' => 'Members deleted successfully!'
        ]);
    }
}
