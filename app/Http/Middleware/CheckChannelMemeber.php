<?php

namespace App\Http\Middleware;

use App\Models\Channel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckChannelMemeber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authUser = $request->user();
        $userId = data_get($authUser, 'id');

        $channelId = data_get($request, 'channel_id');
        $channel = Channel::find($channelId);

        $isMember = $channel->users()
            ->where('users.id', $userId)
            ->exists();
        if (!$isMember) {
            return response()->json([
                'message' => 'You are not a member of this channel!'
            ], 400);
        }

        return $next($request);
    }
}
