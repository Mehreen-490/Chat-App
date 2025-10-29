<?php

namespace App\Http\Middleware;

use App\Models\Channel;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckChannel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $channelId = data_get($request, 'channel_id');
        $authUser = $request->user();
        $channel = Channel::where('id', $channelId)
            ->where('creator_id', data_get($authUser, 'id'))
            ->first();
        if (!$channel) {
            return response()->json([
                'messaage' => 'Invalid channel id given!'
            ], 400);
        }
        $request->merge([
            'channel' => $channel
        ]);
        return $next($request);
    }
}
